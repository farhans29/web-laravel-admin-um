<x-authentication-layout>
    <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ __('Reset your Password') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400">
                {{ __('Enter your email to receive a password reset link') }}
            </p>
        </div>

        @if (session('status'))
            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            
            <div>
                <x-label for="email" class="mb-2">
                    {{ __('Email Address') }} <span class="text-red-500">*</span>
                </x-label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <x-input id="email" type="email" name="email" :value="old('email')" required autofocus 
                        class="pl-10 w-full" placeholder="your@email.com" />
                </div>
            </div>

            <div>
                <x-button class="w-full justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    {{ __('Send Reset Link') }}
                </x-button>
            </div>
        </form>

        <x-validation-errors class="!mt-6" />

        <!-- Back to login -->
        <div class="text-center text-sm text-gray-500 dark:text-gray-400 pt-4 border-t border-gray-200 dark:border-gray-700">
            {{ __('Remember your password?') }}
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                {{ __('Sign in here') }}
            </a>
        </div>
    </div>
</x-authentication-layout>