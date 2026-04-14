<?php

namespace App\Http\Controllers;

use App\Application\RegisterUser\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentEligibilityController extends Controller
{
    private const PER_PAGE = 12;

    public function __construct(private RegisterUser $registerUser) {}

    public function index(Request $request): View
    {
        $student_id = $request->query('student_id');
        $course = $request->query('course');

        $data = [
            'counts' => $this->registerUser->getStudentEligibilityCounts(),
            'students' => $this->registerUser->getUserAllStudents(
                self::PER_PAGE,
                is_string($student_id) ? $student_id : null,
                is_string($course) ? $course : null,
                null,
            ),
            'courses' => $this->registerUser->getUniqueCourses(),
        ];

        return view('studeneligibility', compact('data', 'course', 'student_id'));
    }
}
