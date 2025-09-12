<x-guest-layout>
    <!-- Page Heading -->
    <h2 class="text-3xl font-extrabold text-center mb-8 text-blue-800 tracking-wide">
        Email Verification
    </h2>

    <!-- Description -->
    <p class="mb-6 text-center text-gray-600 text-lg">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    <!-- Success Status -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-center font-medium text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <!-- Actions -->
    <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
        <!-- Resend Verification Email -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold px-6 py-2 rounded-lg text-lg w-full sm:w-auto">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
