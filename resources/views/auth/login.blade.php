@extends("layouts.app")

@section("content")
    <div
        class="flex min-h-screen items-center justify-center p-4"
        style="background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 50%, #d97706 100%)"
    >
        <div class="flex w-full max-w-5xl flex-col overflow-hidden rounded-3xl bg-white shadow-2xl lg:flex-row">
            <!-- Left Side - Login Form -->
            <div class="flex flex-1 flex-col justify-center p-8 lg:p-12">
                <!-- Logo -->
                <div class="mb-8">
                    <img src="{{ asset("images/logo.png") }}" alt="LOT.BUTTER Logo" class="mx-auto mb-2" />
                </div>

                <!-- Welcome Text -->
                <div class="mb-8 text-center">
                    <h2 class="mb-2 text-2xl font-bold text-gray-900 lg:text-3xl">Hi, Lot.Butter Employee!</h2>
                    <p class="text-gray-600">The faster you work, the luckier you get</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route("login") }}" class="space-y-6">
                    @csrf

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="mb-2 block text-sm font-medium text-gray-900">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Enter your username"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                            required
                        />
                        @error("username")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-900">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                            required
                        />
                        @error("password")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Login Button -->
                    <div class="flex justify-center pt-4">
                        <button
                            type="submit"
                            class="rounded-full bg-gray-900 px-12 py-3 font-medium text-white transition-colors hover:bg-gray-800"
                        >
                            Login
                        </button>
                    </div>
                </form>

                <!-- Terms -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <p class="text-center text-sm text-gray-600">
                        You acknowledge that you read and agree to our
                        <a href="#" class="font-medium hover:underline">Terms of Service</a>
                        and our
                        <a href="#" class="font-medium hover:underline">Privacy Policy</a>
                        .
                    </p>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div class="relative hidden flex-1 lg:block">
                <img
                    src="{{ asset("images/login.jpg") }}"
                    alt="LOT.BUTTER Products"
                    class="h-full w-full object-cover"
                />
            </div>
        </div>
    </div>
@endsection
