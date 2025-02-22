<x-app>
    <div class="container">
        <div class="d-flex flex-row justify-content-center align-items-center">
            <h2>{{$project->name}}</h2>
        </div>
        <div class="d-flex flex-row justify-content-center align-items-center">
            @if($user)
                <h4 class="italic">Created by: {{$user->name}}</h4>
            @endif
        </div>
        <div class="d-flex flex-row justify-content-center align-items-center">
            <p>{{$project->description}}</p>
        </div>
        <hr class="hr-blurry " />
        <div class="row">
            <div class="col-span-12">
                <a href="{{route('projects.edit', $project->id)}}" dusk="edit{{$project->id}}" class="card-link btn btn-warning col-span-2">Edit</a>

                <form class="form-check-inline" method="POST" action="{{route('projects.destroy', $project->id)}}">
                    @csrf
                    @method('DELETE')
                    <a dusk="delete" class="card-link btn btn-danger col-span-2" href="{{route('projects.destroy', $project->id)}}"
                       onclick="event.preventDefault();
                                            this.closest('form').submit();">
                        Delete
                    </a>
                </form>
            </div>
        </div>
        <hr class="hr-blurry " />
        <h3>Texts</h3>
        <div class="row">
            <p class="italic">
                @if(!$texts->count())
                    There is no texts yet...
                @endif
            </p>
            <p>
                <a dusk="create_text" href="{{route('projects.texts.create', ['id' => $project->id])}}">Create new text</a>
            </p>
            <div class="card-deck">
                @foreach($texts as $text)
                    <div class="">
                        <div class="card m-1 w-100" style="width: 20rem;">
                            <div class="card-body">
                                <a dusk="text_{{$text->id}}" class="card-title" href="{{route('texts.show', ['id'=>$text->id])}}">Version {{$text->version}}</a>
                                <p class="card-text italic">{{\Str::of($text->content)->words(30)}}</p>
                            </div>
                            <div class="card-footer">
                                <p class="card-subtitle mb-2 text-muted">Posted: {{$text->created_at->format('H:i d-m-Y')}}
                                @if($text->words)
                                    | Words: {{$text->words}}
                                @endif

                                @if($text->metrics)
                                    |
                                    <a href="#" class="" data-toggle="tooltip" data-placement="top" title="Automated Readability Index">
                                        ARI: {{ number_format($text->readabilityMetrics()->ari->score, 1) }}
                                    </a>
                                    | ARI Age: {{str_replace('_', ' ', $text->readabilityMetrics()->ari->grade_levels[0])}}
                                @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-9">
                {{ $texts->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

</x-app>
