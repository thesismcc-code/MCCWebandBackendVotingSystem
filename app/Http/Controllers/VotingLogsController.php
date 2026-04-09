<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Application\RegisterVotes\RegisterVotes;
use App\Application\RegisterUser\RegisterUser;
use Barryvdh\DomPDF\Facade\Pdf;

class VotingLogsController extends Controller
{
    private readonly RegisterVotes $registerVotes;
    private readonly RegisterUser $registerUser;
    public function __construct(RegisterVotes $registerVotes, RegisterUser $registerUser)
    {
        $this->registerVotes = $registerVotes;
        $this->registerUser = $registerUser;
    }
    public function index(Request $request): View
    {
        $data = $this->registerVotes->getVotingLogs(
            perPage: $request->query('', 9),
            search: $request->query('search'),
            course: $request->query('course'),
            yearLevel: $request->query('year_level'),
        )->withQueryString();
        $courses = $this->registerUser->getUniqueCourses();
        return view('votinglogs', compact('data', 'courses'));
    }
    public function exportPDF(Request $request)
    {
        $data = $this->registerVotes->getAllVotingLogsForExport(
            search: $request->query('search'),
            course: $request->query('course'),
            yearLevel: $request->query('year_level'),
        );
        $pdf = app('dompdf.wrapper')->loadView(
            'pdf.voting-logs-pdf',
            [
                'logs' => $data['logs'],
                'electionName' => $data['election_name'],
                'search' => $request->query('search'),
                'course' => $request->query('courses'),
                'yearLevel' => $request->query('year_level'),
            ]
        )->setPaper('a4', 'landscape');

        return $pdf->download('voting-logs-' . now()->format('Y-m-d') . '.pdf');
    }
}
