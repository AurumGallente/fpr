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
    <div class="row">
        <div class="col-12">
            <form class="">
                <div class="form-group">
                    <label for="text_search">Text search:</label>
                    <textarea dusk="text_search" name="text_search" v-model="text_search" class="form-control" id="text_search" rows="8" >
                    </textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <script>
        let app = {
            data() {
                return {
                    text_search: '{{ old('text_search') ?? '' }}',
                }
            },
            mounted() {

            },
            methods: {

            }
        };
    </script>
</x-app>


