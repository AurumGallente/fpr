<?php

namespace App\Http\Controllers;

use App\Helpers\ReadabilityHelper;
use App\Models\Chunk;
use App\Models\ESChunk;
use App\Models\EStext;
use App\Models\Text;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use App\Models\Project;

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
        return view('texts.show', ['text' => $text, 'EStext' => $EStext, 'ESsearchResults' => $ESsearchResults, 'DBsearchResults' => $DBsearchResults]);
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
     */
    public function search(Request $request): JsonResponse
    {
        $text = $request->get('text');
        $helper = new ReadabilityHelper($text);
        $chunks = collect($helper->chunking());
        $EStext = new EStext();
        $EStext->content = $text;
        $ESchunk = new ESChunk;
        //dd($ESchunk->where('original_content','like',$ESchunk->escapeElasticsearchQuery($text))->distinct()->limit(10)->get(['original_content','external_id', 'score']));
        $searchResult = $EStext->findSimilarByChunks($chunks)->toArray();
        return response()->json([
            'texts' => $EStext->prettySearchResults($searchResult)
        ]);

    }
}
