<?php

use App\Domain\SystemActivity\SystemActivity;
use App\Domain\SystemActivity\SystemActivityRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\mock;

uses(RefreshDatabase::class);

it('records system activity when handling a web request', function () {
    $mock = mock(SystemActivityRepository::class);
    $mock->shouldReceive('createSystemActivity')
        ->once()
        ->with(\Mockery::on(function (SystemActivity $activity) {
            return $activity->getLevel() === 'info'
                && str_contains($activity->getActivity(), 'GET')
                && $activity->getAuthChannel() === 'guest';
        }))
        ->andReturnUsing(function (SystemActivity $systemActivity) {
            return new SystemActivity(
                id: 'test-id',
                userId: $systemActivity->getUserId(),
                level: $systemActivity->getLevel(),
                activity: $systemActivity->getActivity(),
                createdAt: $systemActivity->getCreatedAt(),
                updatedAt: $systemActivity->getUpdatedAt(),
                role: $systemActivity->getRole(),
                httpStatus: $systemActivity->getHttpStatus(),
                routeName: $systemActivity->getRouteName(),
                ipAddress: $systemActivity->getIpAddress(),
                authChannel: $systemActivity->getAuthChannel(),
            );
        });

    $this->app->instance(SystemActivityRepository::class, $mock);

    $response = $this->get('/');

    $response->assertSuccessful();
});

it('attributes the api guard user to the jwt auth channel', function () {
    $id = DB::table('users')->insertGetId([
        'first_name' => 'Test',
        'middle_name' => '',
        'last_name' => 'User',
        'email' => 'jwt-test@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $user = User::query()->findOrFail($id);

    $mock = mock(SystemActivityRepository::class);
    $mock->shouldReceive('createSystemActivity')
        ->once()
        ->with(\Mockery::on(function (SystemActivity $activity) use ($user) {
            return $activity->getAuthChannel() === 'jwt'
                && (string) $user->getAuthIdentifier() === $activity->getUserId()
                && $activity->getRole() === 'admin';
        }))
        ->andReturnUsing(function (SystemActivity $systemActivity) {
            return new SystemActivity(
                id: 'test-id',
                userId: $systemActivity->getUserId(),
                level: $systemActivity->getLevel(),
                activity: $systemActivity->getActivity(),
                createdAt: $systemActivity->getCreatedAt(),
                updatedAt: $systemActivity->getUpdatedAt(),
                role: $systemActivity->getRole(),
                httpStatus: $systemActivity->getHttpStatus(),
                routeName: $systemActivity->getRouteName(),
                ipAddress: $systemActivity->getIpAddress(),
                authChannel: $systemActivity->getAuthChannel(),
            );
        });

    $this->app->instance(SystemActivityRepository::class, $mock);

    $this->actingAs($user, 'api');

    $response = $this->getJson('/api/hello-world');

    $response->assertSuccessful();
});
