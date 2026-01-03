<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\CreateRequest;
use App\Http\Requests\Account\UpdateRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);

        $accounts = User::query()
            ->with('employee.team')
            ->whereHasRole('employee')
            ->when($request->input('name'), function ($query, $name) {
                $query->whereHas('employee', function ($q) use ($name) {
                    $q->where('name', 'like', '%'.$name.'%');
                });
            })
            ->paginate($limit)
            ->withQueryString();

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $employees = Employee::doesntHave('user')->get();

        return view('accounts.create', compact('employees'));
    }

    public function store(CreateRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'employee_id' => $validated['employee_id'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ])->addRole('employee');

        return to_route('admin.accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function edit(User $user)
    {
        $user = $user->load('employee');
        $employees = Employee::whereDoesntHave('user')
            ->orWhere('id', $user->employee_id)
            ->get();

        return view('accounts.edit', compact('user', 'employees'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return to_route('admin.accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return to_route('admin.accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
