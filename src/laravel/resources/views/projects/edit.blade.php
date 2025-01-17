<x-app>
    <div class="d-flex flex-row justify-content-center align-items-center">
        <div class="col-sm-3">
            <h2>Update "{{$project->name}}" project</h2>
            <form method="POST" action="{{ route('projects.update', $project->id) }}">
                @csrf
                @method('PATCH')
                <!-- Name -->
                <div class="form-group">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{old('name') ? old('name') : $project->name}}" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="language_id">Select language</label>
                    <select class="form-control" name="language_id">
                        @foreach ($languages as $language)
                        <option value="{{$language->id}}" {{$project->language->id==$language->id ? 'selected' : ''}}>{{$language->language}}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('language_id')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="3" required>{{old('description') ?: $project->description}}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <x-primary-button class="ms-4">
                    {{ __('Update') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app>
