<?php

use App\Application\RegisterUser\RegisterUser;
use App\Domain\User\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\get;
use function Pest\Laravel\mock;

it('redirects guests to login', function (): void {
    get(route('view.student-eligibility'))->assertRedirect(route('login'));
});

it('redirects comelec away from student eligibility', function (): void {
    Session::put('auth_user', [
        'id' => 'COM-test123',
        'role' => 'comelec',
        'email' => 'comelec@test.com',
        'first_name' => 'Comelec',
        'last_name' => 'User',
        'middle_name' => '',
        'student_id' => null,
        'comelec_id' => 'COM-test123',
        'admin_id' => null,
    ]);

    get(route('view.student-eligibility'))
        ->assertRedirect(route('view.comelec-dashboard'));
});

it('renders student eligibility for sao with mocked data', function (): void {
    Session::put('auth_user', [
        'id' => 'SAO-test123',
        'role' => 'sao',
        'email' => 'sao@test.com',
        'first_name' => 'Sao',
        'last_name' => 'User',
        'middle_name' => '',
        'student_id' => null,
        'comelec_id' => null,
        'admin_id' => null,
    ]);

    $student = new User(
        id: 'STUabc12345678',
        first_name: 'Jane',
        middle_name: '',
        last_name: 'Doe',
        email: 'jane.doe@school.edu',
        password: '',
        role: 'student',
        admin_id: null,
        student_id: 'STU-026-001',
        course: 'Computer Science',
        year_level: '1st Year',
        comelec_id: null,
        email_verified_at: now()->toDateTimeLocalString(),
        created_at: now()->toDateTimeLocalString(),
        updated_at: now()->toDateTimeLocalString(),
    );

    $paginator = new LengthAwarePaginator(
        collect([$student]),
        1,
        12,
        1,
        ['path' => route('view.student-eligibility')]
    );

    mock(RegisterUser::class, function ($mock) use ($paginator): void {
        $mock->shouldReceive('getStudentEligibilityCounts')
            ->once()
            ->andReturn([
                'total' => 10,
                'eligible' => 7,
                'not_eligible' => 3,
            ]);
        $mock->shouldReceive('getUserAllStudents')
            ->once()
            ->andReturn($paginator);
        $mock->shouldReceive('getUniqueCourses')
            ->once()
            ->andReturn(['Computer Science']);
    });

    $response = get(route('view.student-eligibility'));

    $response->assertSuccessful();
    $response->assertSee('Student Eligibility', false);
    $response->assertSee('10', false);
    $response->assertSee('7', false);
    $response->assertSee('3', false);
    $response->assertSee('jane.doe@school.edu', false);
});
