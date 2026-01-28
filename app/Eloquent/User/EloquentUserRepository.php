<?php

namespace App\Eloquent\User;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Exceptions\UserPersistenceException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentUserRepository implements UserRepository
{

    public function findById(int $id): User
    {
        try {
            $userData = UserModel::findOrFail($id);
            return $this->mapToEntity($userData);
        } catch (ModelNotFoundException $e) {
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

            return $this->mapToEntity($new_user);
        } catch (\Throwable $e) {
            throw new UserPersistenceException("save new user: " . $e->getMessage());
        }
    }

    public function updateUser(User $data): User
    {
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

            return $this->mapToEntity($user);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException($data->getId());
        } catch (\Throwable $e) {
            throw new UserPersistenceException("update user", $e);
        }
    }

    public function deleteUser(int $id): void
    {
        try {
            $user = UserModel::findOrFail($id);
            $user->delete();
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException($id);
        } catch (\Throwable $e) {
            throw new UserPersistenceException("delete user", $e);
        }
    }

    public function allUsers(): array
    {
        try {
            $users = UserModel::all();

            return $users->map(function ($user) {
                return $this->mapToEntity($user);
            })->toArray();
        } catch (\Throwable $e) {
            throw new UserPersistenceException("retrieve all users", $e);
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
