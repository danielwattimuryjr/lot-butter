@extends("layouts.dashboard")

@section("title", "Create Employee")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Create Employee</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("admin.employees.store") }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old("name") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("name")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nip" class="mb-2 block text-sm font-medium text-gray-900">NIP</label>
                    <input
                        type="text"
                        id="nip"
                        name="nip"
                        value="{{ old("nip") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        maxlength="11"
                        required
                    />
                    @error("nip")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="mb-2 block text-sm font-medium text-gray-900">Phone</label>
                    <input
                        type="text"
                        id="phone_number"
                        name="phone_number"
                        value="{{ old("phone_number") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        maxlength="12"
                        required
                    />
                    @error("phone_number")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="team" class="mb-2 block text-sm font-medium text-gray-900">Team</label>
                    <select
                        id="team"
                        name="team_id"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                    >
                        <option selected disabled>-- CHOOSE TEAM --</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ old("team_id") == $team->id ? "selected" : "" }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    @error("team_id")
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
