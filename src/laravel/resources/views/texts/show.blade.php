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
                <div class="card p-1">
                    @if($text->words)
                        <p>Words: {{$text->words}}</p>
                    @endif

                    @if($text->metrics)
                        <p>
                            Automated Readability Index:
                            <mark>{{ number_format($text->readabilityMetrics()->ari->score, 1) }}</mark>
                        </p>
                        <p>
                            ARI Grade Level:
                            <mark>{{str_replace('_', ' ', $text->readabilityMetrics()->ari->grade_levels[0])}}</mark>
                        </p>
                        <p>
                            Dale Chall Readability:
                            <mark>{{ number_format($text->readabilityMetrics()->dale->score, 1) }}</mark>
                        </p>
                        <p>
                            Dale Chall Grade Level:
                            <mark>{{str_replace('_', ' ', $text->readabilityMetrics()->dale->grade_levels[0])}}</mark>
                        </p>
                        <p>
                            Flesch Score:
                            <mark>{{ number_format($text->readabilityMetrics()->flesch->score, 1) }}</mark>
                        </p>
                        <p>
                            Flesch Reading Ease:
                            <mark>{{str_replace('_', ' ', $text->readabilityMetrics()->flesch->ease)}}</mark>
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <h3 class="text-center">Content</h3>
            <div class="lead bg-white text-black">{{$text->content}}</div>
        </div>
    </div>
</x-app>
