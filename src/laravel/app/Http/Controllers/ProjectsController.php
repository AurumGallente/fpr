<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ProjectsController extends Controller implements HasMiddleware
{
    const PER_PAGE=10;

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
    public function index(Request $request): View
    {
        $projects = Project::orderBy('id','desc')->paginate(self::PER_PAGE);

        return view('projects.index', ['projects' => $projects]);
    }

    public function create(): View
    {
        $languages = Language::all();
        return view('projects.create', ['languages' => $languages]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:projects,name', 'max:255'],
            'language_id' => ['required', 'exists:languages,id'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $project = Project::create([
            'name' => $request->name,
            'language_id' => $request->language_id,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return redirect(route('projects.index', absolute: false));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {
        $project = Project::find($request->id);
        $user = $project->user;
        $texts = $project->texts()->orderBy('version', 'desc')->paginate(self::PER_PAGE);
        return view('projects.show', [
            'project' => $project,
            'user' => $user,
            'texts' => $texts,
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        $project = Project::find($request->id);
        $languages = Language::all();
        return view('projects.edit', ['project' => $project, 'languages' => $languages]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $project = Project::find($request->id);
        $request->validate([
            'name' => ['required', 'string', "unique:projects,name,$project->id", 'max:255'],
            'language_id' => ['required', 'exists:languages,id'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
        ]);
        $project->update([
            'name' => $request->name,
            'language_id' => $request->language_id,
            'description' => $request->description
        ]);
        return redirect(route('projects.index', absolute: false));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $project = Project::find($request->id);
        $project->delete();
        return redirect(route('projects.index', absolute: false));
    }


}
