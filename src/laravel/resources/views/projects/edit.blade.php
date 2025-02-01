<x-app>
    <div class="row">
        <div class="form-group">
            <h2>Update "{{$project->name}}" project</h2>
            <form method="POST" action="{{ route('projects.update', $project->id) }}">
                @csrf
                @method('PATCH')
                <!-- Name -->
                <div class="form-group">
                    <label class="label" for="name" :value="__('Name')" >Project's Name</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{old('name') ? old('name') : $project->name}}" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="language_id">Select language</label>
                    <select class="form-control" name="language_id">
                        @foreach ($languages as $language)
                        <option class="" value="{{$language->id}}" {{$project->language->id==$language->id ? 'selected' : ''}}>{{$language->language}}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('language_id')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="10" required>{{old('description') ?: $project->description}}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <button class="btn btn-primary" type="submit">
                    {{ __('Update') }}
                </button>
            </form>
        </div>
    </div>
</x-app>
