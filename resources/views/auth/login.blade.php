<x-guest-layout>

   @if(session('success'))
    <div id="success-alert" class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 text-center font-semibold relative">
            {{ session('success') }}
            <button onclick="document.getElementById('success-alert').style.display='none'"
                    class="absolute  right-3 text-green-900 font-bold">
                &times;
            </button>
        </div>
    @endif




    <h2 class="text-3xl font-extrabold text-center mb-8 text-blue-800 tracking-wide">
        Login
    </h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 text-lg text-gray-900 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-black font-semibold text-lg" />
            <x-text-input id="email"
                          class="block mt-1 w-full rounded-lg  p-2 border border-gray-400 bg-white/80 
                                 text-lg placeholder:text-gray-500 
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autofocus autocomplete="username"
                          placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-black font-semibold text-lg" />
            <x-text-input id="password"
                          class="block mt-1 w-full rounded-lg  p-2  border border-gray-400 bg-white/80 
                                 text-lg placeholder:text-gray-500 
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="password"
                          name="password"
                          required autocomplete="current-password"
                          placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Remember Me and Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-400 text-blue-800 focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-black font-medium text-base">{{ __('Remember me') }}</label>
            </div>

            @if (Route::has('password.request'))
                <a class="underline text-base text-blue-900 font-medium hover:text-blue-600" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="flex justify-center">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg text-lg w-full sm:w-auto">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
