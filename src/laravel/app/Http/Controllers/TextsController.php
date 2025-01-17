<?php

namespace App\Http\Controllers;

use App\Models\Text;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use App\Models\Project;

class TextsController extends Controller implements HasMiddleware
{
    /**
     * @return array|Middleware[]
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('auth'),
        ];
    }

    /**
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $project = Project::find($request->id);
        $lastText = $project->lastText();
        return view('texts.create', [
            'project' => $project,
            'lastText' => $lastText
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => ['required', 'string', 'min:100'],
        ]);

        $previousText = Project::find($request->project_id)
            ->lastText();
        $version = $previousText ? $previousText->version + 1 : 1;
        $text = Text::create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'version' => $version,
        ]);
        return redirect(route('projects.show', ['id' => $request->project_id]));
    }
}
