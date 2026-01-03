@extends("layouts.dashboard")

@section("title", "Create Account")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Create Account</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("admin.accounts.store") }}" class="space-y-6">
                @csrf

                <div>
                    <label for="employee" class="mb-2 block text-sm font-medium text-gray-900">Employee</label>
                    <select
                        id="employee"
                        name="employee_id"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                    >
                        <option selected disabled>-- CHOOSE EMPLOYEE --</option>
                        @foreach ($employees as $employee)
                            <option
                                value="{{ $employee->id }}"
                                {{ old("employee_id") == $employee->id ? "selected" : "" }}
                            >
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    @error("employee_id")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="mb-2 block text-sm font-medium text-gray-900">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old("username") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("username")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-gray-900">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("password")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-12 py-3 font-medium text-white transition-colors hover:bg-gray-800"
                >
                    Create
                </button>
            </form>
        </div>
    </div>
@endsection
