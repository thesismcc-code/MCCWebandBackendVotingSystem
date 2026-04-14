<?php

namespace App\Http\Controllers;

use App\Application\RegisterUser\RegisterUser;
use App\Application\RegisterVotes\RegisterVotes;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SAOVoterParticipationController extends Controller
{
    public function __construct(
        private readonly RegisterVotes $registerVotes,
        private readonly RegisterUser $registerUser
    ) {}

    public function index(Request $request): View
    {
        $yearLevelFilter = $this->normalizeYearLevel($request->query('year_filter'));

        $voters = $this->registerVotes->getVotingLogs(
            perPage: 9,
            search: $request->query('search'),
            course: $request->query('course_filter'),
            yearLevel: $yearLevelFilter,
        )->withQueryString();

        $voters->setCollection(
            $voters->getCollection()->map(function (array $voter): object {
                return (object) [
                    'voter_id' => $voter['voter_id'] ?? null,
                    'student_id' => $voter['student_id'] ?? 'Unknown',
                    'name' => $voter['name'] ?? 'Unknown',
                    'course_name' => $voter['course'] ?? 'Unknown',
                    'year_level' => $voter['year_level'] ?? 'Unknown',
                    'voted_at' => $voter['voted_at'] ?? null,
                    'status' => $voter['status'] ?? 'Voted',
                ];
            })
        );

        $courses = $this->registerUser->getUniqueCourses();

        return view('saovoterparticipation', compact('voters', 'courses'));
    }

    private function normalizeYearLevel(?string $yearFilter): ?string
    {
        if ($yearFilter === null || $yearFilter === '') {
            return null;
        }

        return match ($yearFilter) {
            '1' => '1st Year',
            '2' => '2nd Year',
            '3' => '3rd Year',
            '4' => '4th Year',
            default => $yearFilter,
        };
    }
}
