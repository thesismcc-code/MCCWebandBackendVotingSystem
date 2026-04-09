<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Application\RegisterVotes\RegisterVotes;
use App\Application\RegisterUser\RegisterUser;

class VotingLogsController extends Controller
{
    private readonly RegisterVotes $registerVotes;
    private readonly RegisterUser $registerUser;
    public function __construct(RegisterVotes $registerVotes, RegisterUser $registerUser){
        $this->registerVotes = $registerVotes;
        $this->registerUser = $registerUser;
    }
    public function index(Request $request): View
    {
        $data = $this->registerVotes->getVotingLogs(
            perPage: $request->query('',9),
            search: $request->query('search'),
            course: $request->query('course'),
            yearLevel: $request->query('year_level'),
        )->withQueryString();
        $courses = $this->registerUser->getUniqueCourses();
        return view('votinglogs', compact('data', 'courses'));
    }
}
