<?php

namespace App\Http\Controllers;

use App\Helpers\ReadabilityHelper;
use App\Models\Project;
use App\Models\Text;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use PharIo\Manifest\Author;

class HomeController extends Controller implements HasMiddleware
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('auth', only: ['home']),
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $projectCount = Project::count('id');
        $textCount = Text::count('id');
        $userCount = User::count('id');
        $authorCount = Project::distinct('user_id')->count('user_id');
        return view('home', compact('projectCount', 'textCount', 'userCount', 'authorCount'));
    }
}
