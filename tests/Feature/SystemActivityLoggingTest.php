<?php

use App\Application\RegisterAuth\RegisterAuth;
use App\Domain\SystemActivity\SystemActivity;
use App\Domain\SystemActivity\SystemActivityRepository;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\mock;

uses(TestCase::class, RefreshDatabase::class);

it('records system activity when handling a web request', function () {
    /** @var TestCase $this */
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
    /** @var TestCase $this */
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

it('records a warning when staff login fails with invalid credentials', function () {
    /** @var TestCase $this */
    $created = [];
    $mock = mock(SystemActivityRepository::class);
    $mock->shouldReceive('createSystemActivity')
        ->twice()
        ->andReturnUsing(function (SystemActivity $systemActivity) use (&$created) {
            $created[] = $systemActivity;

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

    $registerAuth = mock(RegisterAuth::class);
    $registerAuth->shouldReceive('login')
        ->once()
        ->with('nobody@example.com', 'password123')
        ->andThrow(new \InvalidArgumentException('Invalid email or password.'));
    $this->app->instance(RegisterAuth::class, $registerAuth);

    $response = $this->post('/login', [
        'email' => 'nobody@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect();
    expect($created)->toHaveCount(2);
    $staffFailed = collect($created)->first(fn (SystemActivity $a) => str_contains($a->getActivity(), 'Staff login failed'));
    expect($staffFailed)->not->toBeNull();
    expect($staffFailed->getLevel())->toBe('warning');
    expect(collect($created)->filter(fn (SystemActivity $a) => $a->getLevel() === 'info')->count())->toBe(1);
});

it('redirects guests away from recent error logs json', function () {
    /** @var TestCase $this */
    $response = $this->getJson(route('view.system-activity.recent-errors'));

    $response->assertRedirect(route('login'));
});

it('returns json for recent error logs when session is admin', function () {
    /** @var TestCase $this */
    $mock = mock(SystemActivityRepository::class);
    $mock->shouldReceive('getErrorLogsSince')->once()->with('', 'all', 'all')->andReturn([]);
    $mock->shouldReceive('createSystemActivity')
        ->once()
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

    $response = $this->withSession([
        'auth_user' => [
            'id' => '1',
            'role' => 'admin',
        ],
    ])->getJson(route('view.system-activity.recent-errors'));

    $response->assertSuccessful();
    $response->assertJson(['data' => []]);
});
