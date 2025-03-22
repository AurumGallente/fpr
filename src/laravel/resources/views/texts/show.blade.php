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

        @if($DBsearchResults->count())
            <h2 class="text-center text-warning">Matches found! ({{$DBsearchResults->count()}})</h2>
            <p>Here are some of them:</p>
            @foreach($DBsearchResults as $searchResult)
                <div class="row">
                    <div class="col-12 ">
                        Project: <a href="{{route('projects.show', ['id' => $searchResult->project_id])}}" target="_blank">{{$searchResult->project->name}}</a>,
                        Text ID: <a href="{{route('texts.show', ['id' => $searchResult->id])}}">{{$searchResult->id}}</a>
                        <p>
                            @foreach($text->getCommonChunks($searchResult) as $chunk)
                                ..<span class="text-warning">
                                    {{$chunk->content}}
                                </span>..
                            @endforeach
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <h3 class="text-center text-success">No strict matches found.</h3>
        @endif


        @if($ESsearchResults->count())
            <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Some similar texts found. Perhaps, worth checking:</a>
        @endif
        <div class="row">
            <div class="col-12 collapse multi-collapse" id="multiCollapseExample1">
                <p>Here are some of them:</p>
                <div v-for="item in items" :key="item.project_id">
                    <p>Project: <a v-bind:href="item.project_href" target="_blank">@{{item.project_name}}</a>,</p>
                    <p>Text ID: <a v-bind:href="item.text_href">@{{item.text_id}}</a></p>
                    <p><span class="text-decoration-underline">Common longest string</span>: <span>@{{item.substring}}</span></p>
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
    @push('custom-scripts')
        <script type="module">
            const { createApp, ref } = Vue
            createApp({
                setup() {
                    const text_search = ref('');
                    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                    const headers = new Headers({
                        "Content-Type": "application/json",
                        'X-XSRF-TOKEN': csrfToken
                    });
                    const token = "{{ csrf_token() }}";
                    const text = "{{json_encode($text->content)}}";
                    const items = ref([
                    @foreach($ESsearchResults as $searchResult)
                            <?php $plagiarismProject = \App\Models\Project::withTrashed()->where('id', $searchResult->project_id)->first(); ?>
                            <?php if(!$plagiarismProject) { continue; } ?>

                    {
                        project_href: "{{route('projects.show', ['id' => $plagiarismProject->id])}}",
                        project_name: "{{$plagiarismProject->name}}",
                        text_href:    "{{route('texts.show', ['id' => $searchResult->external_id])}}",
                        text_id:       {{$searchResult->external_id}},
                        content:      "{{json_encode($searchResult->original_content)}}",
                        substring:    "Loading...",
                    },
                    @endforeach
                    ])
                    async function diff_request(){
                        const requests = this.items.map(item =>
                            axios.post("{{route('texts.compare')}}", {
                                headers: headers,
                                text1: text,
                                text2: item.content,
                            })
                                .then(response => {
                                    item.substring = response.data.substring.longest_common_sequence;
                                })
                        );
                        await Promise.all(requests);
                    }
                    return {
                        text_search,
                        items,
                        diff_request
                    }
                },
                mounted: function(){
                    this.diff_request();
                }
            }).mount('#app')
        </script>
    @endpush
</x-app>
