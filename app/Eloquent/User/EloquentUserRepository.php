<?php

namespace App\Eloquent\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserPersistenceException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Kreait\Firebase\Database;

class EloquentUserRepository implements UserRepository
{
    private Database $firebaseDatabase;
    private string $firebaseCollections = 'users';

    public function __construct()
    {
        $this->firebaseDatabase = app('firebase.database');
    }

    public function findById(int $id): User
    {
        try {
            $userData = UserModel::findOrFail($id);
            $this->verifyFirebaseData($id, $userData);
            return $this->mapToEntity($userData);
        } catch (ModelNotFoundException $e) {

            // Fallback: Try to find in Firebase
            try {
                $reference = $this->firebaseDatabase->getReference($this->firebaseCollection . '/' . $id);
                $snapshot = $reference->getSnapshot();

                if ($snapshot->exists()) {
                    $firebaseData = $snapshot->getValue();
                    \Log::warning("User {$id} found in Firebase but not in MySQL. Data inconsistency detected.");
                    return $this->mapFirebaseDataToEntity($id, $firebaseData);
                }
            } catch (\Throwable $firebaseError) {
                \Log::error("Firebase lookup failed for user {$id}: " . $firebaseError->getMessage());
            }

            throw new UserNotFoundException($id);
        } catch (\Throwable $e) {
            throw new UserPersistenceException("retrieve user by ID {$id}", $e);
        }
    }


    public function findByEmail(string $email): User
    {
        try {
            $user_data = UserModel::where('email', $email)->firstOrFail();
            return $this->mapToEntity($user_data);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException("with email {$email}");
        } catch (\Throwable $e) {
            throw new UserPersistenceException("retrieve user with email {$email}: " . $e->getMessage());
        }
    }

    public function saveNewUser(User $data): User
    {
        \DB::beginTransaction();

        try {

            $new_user = new UserModel();
            $new_user->uuid = $data->getUid();
            $new_user->first_name = $data->getFirstName();
            $new_user->middle_name = $data->getMiddleName();
            $new_user->last_name = $data->getLastName();
            $new_user->email = $data->getEmail();
            $new_user->password = $data->getPassword();
            $new_user->role = $data->getRole();
            $new_user->save();

            $this->saveToFirebase($new_user);

            \DB::commit();

            return $this->mapToEntity($new_user);
        } catch (\Throwable $e) {
            \DB::rollBack();

            if(isset($new_user)){
                $this->deleteFromFirebase($new_user->id);
            }

            throw new UserPersistenceException("save new user: " . $e->getMessage());
        }
    }

    public function updateUser(User $data): User
    {

        \DB::beginTransaction();

        try {
            $user = UserModel::findOrFail($data->getId());

            $user->uuid = $data->getUid();
            $user->first_name = $data->getFirstName();
            $user->middle_name = $data->getMiddleName();
            $user->last_name = $data->getLastName();
            $user->email = $data->getEmail();
            $user->password = $data->getPassword();
            $user->role = $data->getRole();
            $user->save();

            $this->updateInFirebase($user);

            \DB::commit();

            return $this->mapToEntity($user);
        } catch (ModelNotFoundException $e) {
            \DB::rollBack();
            throw new UserNotFoundException($data->getId());
        } catch (\Throwable $e) {
            \DB::rollBack();
            throw new UserPersistenceException("update user", $e);
        }
    }

    public function deleteUser(int $id): void
    {
        \DB::beginTransaction();

        try {
            $user = UserModel::findOrFail($id);
            $user->delete();

            $this->deleteFromFirebase($id);
            \DB::commit();
        } catch (ModelNotFoundException $e) {
            \DB::rollBack();
            throw new UserNotFoundException($id);
        } catch (\Throwable $e) {
            \DB::rollBack();
            throw new UserPersistenceException("delete user", $e);
        }
    }

    public function allUsers(): array
{
    try {
        // 1. Fetch all users from MySQL
        $mysqlUsers = UserModel::all();
        $mysqlUsersById = $mysqlUsers->keyBy('id');

        // 2. Fetch all users from Firebase
        $firebaseUsers = $this->getAllUsersFromFirebase();

        // 3. Merge users from both sources
        $allUsers = [];
        $processedIds = [];

        // Add all MySQL users first
        foreach ($mysqlUsers as $user) {
            $allUsers[] = $this->mapToEntity($user);
            $processedIds[] = $user->id;

            // Verify this user exists in Firebase, if not sync it
            if (!isset($firebaseUsers[$user->id])) {
                \Log::warning("User {$user->id} missing from Firebase. Syncing...");
                $this->saveToFirebase($user);
            }
        }

        foreach ($firebaseUsers as $firebaseId => $firebaseData) {
            // Firebase push IDs are NOT numeric MySQL IDs
            if (!is_numeric($firebaseId)) {
                \Log::warning("Skipping Firebase-only user with push ID {$firebaseId}");
                $allUsers[] = $this->mapFirebaseDataToEntity($firebaseId, $firebaseData);
                // dd($allUsers);
                continue;
            }
            if (!in_array((int)$firebaseId, $processedIds)) {
                $this->syncFromFirebaseToMySQL((int)$firebaseId, $firebaseData);
                $allUsers[] = $this->mapFirebaseDataToEntity($firebaseId, $firebaseData);
            }
        }

        return $allUsers;
    } catch (\Throwable $e) {
        throw new UserPersistenceException("retrieve all users", $e);
    }
}

/**
 * Get all users from Firebase
 */
private function getAllUsersFromFirebase(): array
{
    try {
        $snapshot = $this->firebaseDatabase->getReference('users')->getSnapshot();

        return $snapshot->getValue() ?? [];
    } catch (\Throwable $e) {
        \Log::error("Failed to fetch users from Firebase: " . $e->getMessage());
        return [];
    }
}

/**
 * Sync user from Firebase back to MySQL
 */
private function syncFromFirebaseToMySQL(int $id, array $firebaseData): void
{
    try {
        $user = new UserModel();
        $user->id = $id;
        $user->uuid = $firebaseData['uuid'] ?? '';
        $user->first_name = $firebaseData['first_name'] ?? '';
        $user->middle_name = $firebaseData['middle_name'] ?? '';
        $user->last_name = $firebaseData['last_name'] ?? '';
        $user->email = $firebaseData['email'] ?? '';
        $user->password = $firebaseData['password'] ?? '';
        $user->role = $firebaseData['role'] ?? '';

        // Handle timestamps
        if (isset($firebaseData['created_at'])) {
            $user->created_at = $firebaseData['created_at'];
        }
        if (isset($firebaseData['updated_at'])) {
            $user->updated_at = $firebaseData['updated_at'];
        }

        $user->save();

        \Log::info("User {$id} synced from Firebase to MySQL");
    } catch (\Throwable $e) {
        \Log::error("Failed to sync user {$id} from Firebase to MySQL: " . $e->getMessage());
    }
}

/**
 * Map Firebase data to Domain entity
 */
private function mapFirebaseDataToEntity(string $firebaseId, array $data): User
{
    // return new User(
    //     $id,
    //     $data['uuid'] ?? '',
    //     $data['first_name'] ?? '',
    //     $data['middle_name'] ?? '',
    //     $data['last_name'] ?? '',
    //     $data['email'] ?? '',
    //     $data['password'] ?? '',
    //     $data['role'] ?? '',
    //     $data['created_at'] ?? now()->toDateTimeString(),
    //     $data['updated_at'] ?? now()->toDateTimeString()
    // );

    return new User(
        0, // No MySQL ID yet
        $firebaseId, // store Firebase push ID as uuid
        $data['name'] ?? '',
        null,
        null,
        $data['email'] ?? '',
        '', // no password in Firebase
        $data['role'] ?? 'user',
        $data['created_at'] ?? now()->toDateTimeString(),
        $data['updated_at'] ?? now()->toDateTimeString()
    );
}
    /**
     * Save user data to Firebase Realtime Database.
     * **/
    public function saveToFirebase(User $user): void{
        try{
            $reference = $this->firebaseDatabase->getReference('users/' . $user->id);

            $reference->set([
                'id' => $user->getId(),
                'uuid' => $user->getUid(),
                'first_name' => $user->getFirstName(),
                'middle_name' => $user->getMiddleName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'created_at' => $user->getCreatedAt(),
                'updated_at' => $user->getUpdatedAt(),
            ]);
        } catch (\Throwable $e){
            throw new UserPersistenceException("save user to firebase: " . $e->getMessage());
        }
    }

    /**
     * Update user data in Firebase
     */
    private function updateInFirebase(UserModel $user): void
    {
        try {
            $reference = $this->firebaseDatabase->getReference($this->firebaseCollection . '/' . $user->id);

            $reference->update([
                'uuid' => $user->uuid,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'password' => $user->password,
                'role' => $user->role,
                'updated_at' => $user->updated_at->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            throw new \Exception("Failed to update in Firebase: " . $e->getMessage());
        }
    }
    /**
     * Delete user data from Firebase
     */
    private function deleteFromFirebase(int $id): void
    {
        try {
            $reference = $this->firebaseDatabase->getReference($this->firebaseCollection . '/' . $id);
            $reference->remove();
        } catch (\Throwable $e) {
            throw new \Exception("Failed to delete from Firebase: " . $e->getMessage());
        }
    }

    /**
     * Verify Firebase data matches MySQL (runs in background)
     * **/
    private function verifyFirebaseData(int $id, UserModel $userModel):void{
        try{
            $reference = $this->firebaseDatabase->getReference($this->firebaseCollections . '/' . $id);
            $snapshot = $reference->getSnapshot();

            if(!$snapshot->exists()){
                // we sync the data
                \Log::info("Firebase data missing for user ID {$id}. Syncing data.");
                $this->saveToFirebase($userModel);
            } else {
                // Optional: Verify data consistency
            $firebaseData = $snapshot->getValue();
            if ($firebaseData['email'] !== $mysqlUser->email) {
                \Log::warning("Data mismatch for user {$id}. MySQL email: {$mysqlUser->email}, Firebase email: {$firebaseData['email']}");
            }
            }
        } catch(\Throwable $e){
            \Log::error("Error verifying Firebase data for user ID {$id}: " . $e->getMessage());
        }
    }

    /**
     * Map Eloquent model to Domain entity
     */
    private function mapToEntity(UserModel $model): User
    {
        return new User(
            $model->id,
            $model->uuid,
            $model->first_name,
            $model->middle_name,
            $model->last_name,
            $model->email,
            $model->password,
            $model->role,
            $model->created_at,
            $model->updated_at
        );
    }
}
