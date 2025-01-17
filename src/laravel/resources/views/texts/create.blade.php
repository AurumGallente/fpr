<x-app>
    <div class="row">
        <h5>
            Project: {{$project->name}}
        </h5>
        <p>
            <b>Description:</b> {{$project->description}}
        </p>
        <p>
            <b>Language:</b> {{$project->language->language}}
        </p>
        <p>
            <b>Created:</b> {{$project->created_at->format('d-m-Y')}}
        </p>
        <p>
            <b>By:</b> {{$project->user->email}}
        </p>
    </div>
    <div class="row">
        <div class="col-10">
            <form method="POST" action="{{route('projects.texts.store',['project_id' => $project->id])}}">
                @csrf
                @method('POST')
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea class="form-control" id="content" name="content" rows="12" required>{{old('content')}}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>
                <br>
                <div class="form-group">
                    <button type="submit" class="input-group-addon btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</x-app>
