<x-guest-layout>
    <div class="row">
        <div class="col-6">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input dusk="email" id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input dusk="password" id="password" class="form-control"
                                  type="password"
                                  name="password"
                                  required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="form-group">
                    <x-primary-button dusk="submit" class="btn btn-primary">
                        {{ __('Log in') }}
                    </x-primary-button>
                    @if (Route::has('password.request'))
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('password.request') }}" class="btn btn-info mb-1">{{ __('Forgot your password?') }}</a>
                        </div>
                    @endif
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-secondary mb-1" href="{{route('register')}}">Sign up</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
