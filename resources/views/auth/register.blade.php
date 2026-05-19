<x-guest-layout>
    <div class="min-h-screen flex">

        <!-- LEFT SIDE -->
        <div class="hidden md:flex w-1/2 bg-gradient-to-br from-purple-600 to-indigo-600 text-white items-center justify-center">
            <div class="text-center px-10">
                <h1 class="text-4xl font-extrabold mb-4">Join With Us 🚀</h1>
                <p class="text-lg opacity-80">
                    Buat akun dan mulai perjalanan digital kamu sekarang.
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-50">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl">

                <!-- Title -->
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                    Create Account ✨
                </h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required />
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

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password_confirmation"
                            required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Action -->
                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('login') }}"
                            class="text-sm text-indigo-600 hover:underline">
                            Already have account?
                        </a>

                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                            Register
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</x-guest-layout>