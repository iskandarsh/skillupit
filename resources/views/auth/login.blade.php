<x-guest-layout>
    <div class="min-h-screen flex">

        <!-- LEFT SIDE (Branding / Image) -->
        <div class="hidden md:flex w-1/2 bg-gradient-to-br from-indigo-600 to-purple-600 text-white items-center justify-center">
            <div class="text-center px-10">
                <h1 class="text-4xl font-extrabold mb-4">Welcome Back 👋</h1>
                <p class="text-lg opacity-80">
                    Silakan login untuk melanjutkan ke dashboard aplikasi kamu.
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE (FORM) -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl">

                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                    Login Account 🚀
                </h2>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-indigo-600 hover:underline">
                            Forgot Password?
                        </a>
                        @endif
                    </div>

                    <!-- Button -->
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition duration-200">
                            Log in
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-guest-layout>