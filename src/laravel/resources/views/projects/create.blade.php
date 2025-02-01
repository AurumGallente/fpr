<x-app>
    <div class="d-flex flex-row justify-content-center align-items-center">
        <div class="col-sm-8">
            <h2>Create a new project</h2>
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf
                <!-- Name -->
                <div class="form-group">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="language_id">Select language</label>
                    <select class="form-control" name="language_id">
                        @foreach ($languages as $language)
                            <option value="{{$language->id}}" {{old('$language->id')==$language->id ? 'selected' : ''}}>{{$language->language}}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('language_id')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="12" required>{{old('description')}}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
                <div class=""></div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app>
