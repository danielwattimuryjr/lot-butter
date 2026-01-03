@extends("layouts.dashboard")

@section("title", "Account")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Employee</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex flex-wrap gap-3">
                <!-- Export CSV Button -->
                <a
                    href="{{ route("export", ["resource" => "accounts", "format" => "csv"]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                >
                    <x-heroicon-o-document class="h-4 w-4" />
                    Export CSV
                </a>

                <!-- Print PDF Button -->
                <a
                    href="{{ route("export", ["resource" => "accounts", "format" => "pdf"]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                >
                    <x-heroicon-o-printer class="h-4 w-4" />
                    Print PDF
                </a>
            </div>

            <!-- Add New Employee Button -->
            <a
                href="{{ route("admin.accounts.create") }}"
                class="inline-flex items-center gap-2 rounded-lg border-2 border-orange-400 bg-transparent px-4 py-2 text-sm font-medium text-orange-400 transition-colors hover:bg-orange-50"
            >
                ADD NEW ACCOUNT
            </a>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <!-- Table Controls -->
            <x-table-controls />

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Employee's Name</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Team</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Username</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($accounts->currentPage() - 1) * $accounts->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $account->employee->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $account->employee->team->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $account->username }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <a
                                            href="{{ route("admin.accounts.edit", $account) }}"
                                            class="text-orange-400 transition-colors hover:text-orange-600"
                                        >
                                            <x-heroicon-o-pencil-square class="h-5 w-5" />
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route("admin.accounts.destroy", $account) }}"
                                            class="inline-flex items-center"
                                        >
                                            @csrf
                                            @method("DELETE")

                                            <button
                                                type="submit"
                                                class="text-orange-400 transition-colors hover:text-red-600"
                                            >
                                                <x-heroicon-o-trash class="h-5 w-5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                    No accounts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-table-pagination :paginator="$accounts" />
        </div>
    </div>
@endsection
