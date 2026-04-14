<?php

namespace App\Http\Controllers;

use App\Application\RegisterAuth\RegisterAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    private RegisterAuth $registerAuth;

    public function __construct(RegisterAuth $registerAuth)
    {
        $this->registerAuth = $registerAuth;
    }

    public function index(): View|RedirectResponse
    {
        $user = $this->registerAuth->getCurrentLoggInUser();

        if (! $user) {
            return redirect('/students')
                ->with('error', 'Please log in to access your profile.');
        }

        return view('student.profile', [
            'profile' => [
                'first_name' => $user->getFirstName(),
                'middle_name' => $user->getMiddleName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'student_id' => $user->getStudentId(),
                'course' => $user->getCourse() ?: 'No Course enrolled',
                'year_level' => $user->getYearLevel(),
            ],
        ]);
    }

    public function updateProfile(Request $request): View|RedirectResponse
    {
        return redirect()->route('view.student-verification');
    }
}
