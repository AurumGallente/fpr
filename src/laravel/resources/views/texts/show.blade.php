<x-app>
    <div class="container">
        <div class="row">
            <hr class=""/>
            <div class="col-6">
                <div class="">
                    <h3>Author: {{$text->user->name}}</h3>
                    <p>Created: {{$text->created_at->format('d-m-Y H:i')}}</p>
                    <p>For project: <a href="{{route('projects.show', ['id' => $text->project->id])}}">{{$text->project->name}}</a></p>
                    <p>Project language: {{$text->project->language->language}}</p>
                </div>
            </div>
            <div class="col-6">
                <div class="card p-1 alert-dismissible alert-primary">
                    @if($text->words)
                        <p>Words: {{$text->words}}</p>
                    @endif

                    @if($text->metrics)
                        <p class="">
                            Automated Readability Index:
                            {{ number_format($text->readabilityMetrics()->ari->score, 1) }}
                        </p>
                        <p>
                            ARI Grade Level:
                            {{str_replace('_', ' ', $text->readabilityMetrics()->ari->grade_levels[0])}}
                        </p>
                        <p>
                            Dale Chall Readability:
                            {{ number_format($text->readabilityMetrics()->dale->score, 1) }}
                        </p>
                        <p>
                            Dale Chall Grade Level:
                            {{str_replace('_', ' ', $text->readabilityMetrics()->dale->grade_levels[0])}}
                        </p>
                        <p>
                            Flesch Score:
                            {{ number_format($text->readabilityMetrics()->flesch->score, 1) }}
                        </p>
                        <p>
                            Flesch Reading Ease:
                            {{str_replace('_', ' ', $text->readabilityMetrics()->flesch->ease)}}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-auto">
                    <h3 class="card-title text-center">Content</h3>
                    <div class="card-body">
                        @foreach(explode("\n", $text->content) as $paragraph)
                            <p class="card-text list-group-item">
                                {{ $paragraph }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
