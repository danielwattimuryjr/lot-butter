@extends("layouts.dashboard")

@section("title", "Account")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">User Accounts</h1>
            <p class="mt-1 text-sm text-gray-600">Manage user access and authentication credentials</p>
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

            <a
                href="{{ route("admin.accounts.create") }}"
                class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
            >
                <x-heroicon-o-plus class="h-5 w-5" />
                Add New Account
            </a>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <!-- Table Controls -->
            <x-table-controls />

            <!-- Table -->
            <div class="mt-4 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Employee Name</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Team</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Username</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($accounts->currentPage() - 1) * $accounts->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-teal-100">
                                            <x-heroicon-o-user class="h-4 w-4 text-teal-600" />
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $account->employee->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700"
                                    >
                                        {{ $account->employee->team->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center text-sm">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <x-heroicon-o-at-symbol class="h-4 w-4 text-gray-400" />
                                        <span class="font-mono text-gray-900">{{ $account->username }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a
                                            href="{{ route("admin.accounts.edit", $account) }}"
                                            class="text-orange-400 transition-colors hover:text-orange-600"
                                            title="Edit"
                                        >
                                            <x-heroicon-o-pencil-square class="h-5 w-5" />
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route("admin.accounts.destroy", $account) }}"
                                            class="inline-flex items-center"
                                            onsubmit="
                                                return confirm(
                                                    'Are you sure you want to delete this account? This will revoke user access.',
                                                );
                                            "
                                        >
                                            @csrf
                                            @method("DELETE")

                                            <button
                                                type="submit"
                                                class="text-orange-400 transition-colors hover:text-red-600"
                                                title="Delete"
                                            >
                                                <x-heroicon-o-trash class="h-5 w-5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100">
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-100 p-4">
                                            <x-heroicon-o-user-circle class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <p class="mt-4 text-sm font-medium text-gray-900">No user accounts found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Create accounts to grant employees access to the system.
                                        </p>
                                        <a
                                            href="{{ route("admin.accounts.create") }}"
                                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-butter-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                                        >
                                            <x-heroicon-o-plus class="h-4 w-4" />
                                            Add First Account
                                        </a>
                                    </div>
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
