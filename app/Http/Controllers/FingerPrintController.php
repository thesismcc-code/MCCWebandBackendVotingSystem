<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Application\RegisterUser\RegisterUser;

class FingerPrintController extends Controller
{
    private RegisterUser $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function index(Request $request): View
    {
        $student_id = $request->get("student_id");
        $course     = $request->get("course");
        $year_level = $request->get("year_level");

        $data = [
            "enrolled_students" => $this->registerUser->countTotalStudents(),
            "enrolled_today"    => $this->registerUser->countTotalStudentsEnrolledToday(),
            "students"          => $this->registerUser->getUserAllStudents(5, $student_id, $course, $year_level),
            "courses"           => $this->registerUser->getUniqueCourses(),
        ];

        return view('fingerprint', compact('data', 'course', 'year_level', 'student_id'));
    }
}
