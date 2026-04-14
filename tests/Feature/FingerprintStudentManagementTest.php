<?php

use App\Application\RegisterUser\RegisterUser;
use Illuminate\Support\Facades\Session;

beforeEach(function (): void {
    Session::put('auth_user', [
        'id' => 'ADM-test123',
        'role' => 'admin',
        'email' => 'admin@test.com',
        'first_name' => 'Admin',
        'last_name' => 'User',
        'middle_name' => '',
        'student_id' => null,
        'comelec_id' => null,
        'admin_id' => 'ADM-test123',
    ]);
});

it('deletes a student when authenticated', function (): void {
    $this->mock(RegisterUser::class, function ($mock): void {
        $mock->shouldReceive('deleteUser')
            ->once()
            ->with('firebase-user-1');
    });

    $response = $this->from(route('view.finger-print'))
        ->post(route('finger-print.student.delete'), [
            'user_id' => 'firebase-user-1',
        ]);

    $response->assertRedirect(route('view.finger-print', []));
    $response->assertSessionHas('success');
});

it('updates a student when authenticated', function (): void {
    $this->mock(RegisterUser::class, function ($mock): void {
        $mock->shouldReceive('updateUser')
            ->once()
            ->with(\Mockery::on(function (array $data): bool {
                return $data['user_id'] === 'u1'
                    && $data['role'] === 'student'
                    && $data['email'] === 's@test.com'
                    && $data['student_id'] === 'STU-026-001'
                    && $data['course'] === 'Computer Science'
                    && $data['year_level'] === '3rd Year';
            }));
    });

    $response = $this->from(route('view.finger-print'))
        ->post(route('finger-print.student.update'), [
            'user_id' => 'u1',
            'first_name' => 'A',
            'middle_name' => 'B',
            'last_name' => 'C',
            'email' => 's@test.com',
            'role' => 'student',
            'student_id' => 'STU-026-001',
            'course' => 'Computer Science',
            'year_level' => '3rd Year',
        ]);

    $response->assertRedirect(route('view.finger-print', []));
    $response->assertSessionHas('success');
});

it('redirects guests away from delete', function (): void {
    Session::flush();

    $response = $this->post(route('finger-print.student.delete'), [
        'user_id' => 'x',
    ]);

    $response->assertRedirect(route('login'));
});

it('validates delete payload', function (): void {
    $response = $this->from(route('view.finger-print'))
        ->post(route('finger-print.student.delete'), []);

    $response->assertSessionHasErrors('user_id');
});
