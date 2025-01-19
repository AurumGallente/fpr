<x-app>
    <div class="container">
        <div class="row">
            <div class="row">
                @foreach($texts as $text)
                    <div class="card m-1" style="width: 18rem;">
                        <div class="card-body">
                            <p class="card-title">For: <a href="{{route('projects.show', ['id' => $text->project->id])}}" class="h6">{{$text->project->name}}</a> : V{{$text->version}}</p>
                            <h6 class="card-subtitle mb-2 text-muted">By: {{$text->user->name}}</h6>
                            <hr class=""/>
                            <p class="card-text">{{\Str::of($text->content)->words(20)}}</p>
                            <a href="{{route('texts.show', ['id' => $text->id])}}" class="card-link">View</a>
                            <div class="card-footer">
                                <small class="text-muted">Posted: {{$text->created_at->format('Y-m-d H:i')}}</small>
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
