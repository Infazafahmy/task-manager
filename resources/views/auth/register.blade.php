<x-guest-layout>
    <!-- Page Heading -->
    <h2 class="text-3xl font-extrabold text-center mb-8 text-blue-800 tracking-wide">
        Register
    </h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 text-lg text-gray-900 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-black font-semibold text-lg" />
            <x-text-input id="name"
                          class="block mt-1 w-full rounded-lg border p-2 border-gray-400 bg-white/80 
                                 text-lg placeholder:text-gray-500 
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name"
                          placeholder="Enter your name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
        </div>

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
                          required
                          autocomplete="username"
                          placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-black font-semibold text-lg" />
            <x-text-input id="password"
                          class="block mt-1 w-full rounded-lg  p-2 border border-gray-400 bg-white/80 
                                 text-lg placeholder:text-gray-500 
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password"
                          placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-black font-semibold text-lg" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full rounded-lg  p-2 border border-gray-400 bg-white/80 
                                 text-lg placeholder:text-gray-500 
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password"
                          placeholder="Confirm your password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Already Registered -->
        <div class="flex items-center justify-between mt-4">
            <a class="underline text-base text-blue-900 font-medium hover:text-blue-600" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg text-lg w-full sm:w-auto">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
