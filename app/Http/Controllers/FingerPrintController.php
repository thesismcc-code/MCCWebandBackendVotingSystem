<?php

namespace App\Http\Controllers;

use App\Application\RegisterUser\RegisterUser;
use App\Http\Requests\DeleteFingerprintStudentRequest;
use App\Http\Requests\UpdateFingerprintStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FingerPrintController extends Controller
{
    public function __construct(private RegisterUser $registerUser) {}

    public function index(Request $request): View
    {
        $student_id = $request->get('student_id');
        $course = $request->get('course');
        $year_level = $request->get('year_level');

        $data = [
            'enrolled_students' => $this->registerUser->countTotalStudents(),
            'enrolled_today' => $this->registerUser->countTotalStudentsEnrolledToday(),
            'students' => $this->registerUser->getUserAllStudents(5, $student_id, $course, $year_level),
            'courses' => $this->registerUser->getUniqueCourses(),
        ];

        return view('fingerprint', compact('data', 'course', 'year_level', 'student_id'));
    }

    public function updateStudent(UpdateFingerprintStudentRequest $request): RedirectResponse
    {
        $redirect = redirect()->route('view.finger-print', $request->fingerprintQueryParams());

        $data = $request->validated();
        unset($data['list_student_id'], $data['list_course'], $data['list_year_level']);

        try {
            $this->registerUser->updateUser($data);

            return $redirect->with('success', 'Student account has been updated successfully.');
        } catch (\InvalidArgumentException $e) {
            Log::info($e->getMessage());

            return $redirect
                ->withErrors(['general' => $e->getMessage()])
                ->withInput()
                ->with('show_edit_modal', true);
        } catch (\Exception $e) {
            Log::info($e->getMessage());

            return $redirect
                ->withErrors(['general' => 'Something went wrong. Please try again.'])
                ->withInput()
                ->with('show_edit_modal', true);
        }
    }

    public function deleteStudent(DeleteFingerprintStudentRequest $request): RedirectResponse
    {
        $redirect = redirect()->route('view.finger-print', $request->fingerprintQueryParams());

        try {
            $this->registerUser->deleteUser($request->validated('user_id'));

            return $redirect->with('success', 'Student account has been deleted successfully.');
        } catch (\Exception $e) {
            Log::info($e->getMessage());

            return $redirect->withErrors(['general' => 'Could not delete this account. Please try again.']);
        }
    }
}
