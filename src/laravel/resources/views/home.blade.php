<x-app>
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">Dashboard</h2>
            <hr class="hr"/>
            <p>
                Projects: {{$projectCount}}
            </p>
            <p>
                Users: {{$userCount}}
            </p>
            <p>
                Active users: {{$authorCount}}
            </p>
            <p>
                Texts: {{$textCount}}
            </p>
        </div>
    </div>
    <div id="es-search" class="row">
        <div class="col-12">
                <div class="form-group">
                    <label for="text_search">Text search:</label>
                    <textarea dusk="text_search" name="text_search" v-model="text_search" class="form-control" id="t_search" rows="8" placeholder="Put your text here to search similar texts">
                    </textarea>
                </div>
                <button v-on:click="es_search" class="btn btn-primary">Submit</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <ul>
                <li class="card" v-for="item in items" :key="index">
                        <a v-bind:href="item.link" target="_blank">
                            <p class="col-6 text-truncate">Text:
                                <span class="italic">
                                    @{{item.common_string}}
                                </span>
                            </p>
                        </a>
                        <a v-bind:href="item.project_link" target="_blank">
                            For project: @{{ item.project_id }}
                        </a>
                </li>
            </ul>
        </div>
    </div>

    @push('custom-scripts')
        <script type="module">
            const { createApp, ref } = Vue
            createApp({
                setup() {
                    const text_search = ref('');
                    const items = ref([]);
                    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                    const headers = new Headers({
                        "Content-Type": "application/json",
                        'X-XSRF-TOKEN': csrfToken
                    });
                    const token = "{{ csrf_token() }}";
                    const search_url = '{{route('texts.search')}}';
                    async function es_search() {
                        const response = await fetch(search_url, {
                            method: 'POST',
                            headers: headers,
                            body: JSON.stringify({ text: text_search.value, _token:token })
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }

                        const data = await response.json();
                        items.value = data.texts;
                        console.log(items);
                    }
                    return {
                        text_search,
                        es_search,
                        items
                    }
                }
            }).mount('#app')
        </script>
    @endpush
</x-app>


