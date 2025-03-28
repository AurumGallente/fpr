<?php

namespace App\Http\Controllers;

use App\Helpers\DiffHelper;
use App\Helpers\ReadabilityHelper;
use App\Models\Chunk;
use App\Models\ESChunk;
use App\Models\EStext;
use App\Models\Text;
use App\Search\SearchEngine;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use App\Models\Project;
use JsonException;

class TextsController extends Controller implements HasMiddleware
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

    /**
     * @param Request $request
     * @return View
     */
    public function show(Request $request): View
    {
        $text = Text::find($request->id);
        $chunks = $text->chunks()->get();
        $EStext = $text->EStext()->first();
        if(!$EStext)
        {
            $EStext = new EStext();
            $EStext->content = $text->content;
            $EStext->normalized_content = $text->content;
        }
        $ESsearchResults = $EStext->findSimilarByChunks($chunks);
        $DBsearchResults = $text->findSimilarByChunks();
        $helper = new DiffHelper();
        return view('texts.show', ['text' => $text, 'EStext' => $EStext, 'ESsearchResults' => $ESsearchResults, 'DBsearchResults' => $DBsearchResults, 'helper' => $helper]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $texts = Text::with(['project', 'user'])
            ->orderBy('id','desc')
            ->paginate(self::PER_PAGE);
        return view('texts.index', ['texts' => $texts]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function search(Request $request): JsonResponse
    {
        $text = $request->get('text');
        $engine = new SearchEngine();
        $matches = $engine->setText($text)
            ->setType(SearchEngine::TYPE_CHUNKS)
            ->setSource(SearchEngine::SRC_ES)
            ->search();
        return response()->json([
            'texts' => $matches
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws JsonException
     */
    public function compare(Request $request): JsonResponse
    {
        $helper = new DiffHelper();
        $substring = $helper->longestCommonSubstring($request->get('text1'), $request->get('text2'));
        return response()->json([
            'substring' => $substring
        ]);
    }
}
