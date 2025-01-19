<x-app>
    <div class="container">
        <div class="row">
            <h3>Author: {{$text->user->name}}</h3>
            <p>Created: {{$text->created_at->format('d-m-Y H:i')}}</p>
            <p>For project: <a href="{{route('projects.show', ['id' => $text->project->id])}}">{{$text->project->name}}</a></p>
            <p>Project language: {{$text->project->language->language}}</p>
        </div>
        <div class="row">
            <h3>Content</h3>
            <div class="lead bg-white text-black">{{$text->content}}</div>
        </div>
    </div>
</x-app>
