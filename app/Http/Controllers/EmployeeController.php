<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\Employee\CreateRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Models\Team;

class EmployeeController extends Controller
{
  public function index()
  {
    $employees = Employee::all();
    return view('employees.index', compact('employees'));
  }

  public function create()
  {
    $teams = Team::get();
    return view('employees.create', compact('teams'));
  }

  public function store(CreateRequest $request)
  {
    Employee::create($request->validated());
    return to_route('admin.employees.index')->with('success', 'Employee created successfully.');
  }

  public function edit(Employee $employee)
  {
    $teams = Team::get();
    return view('employees.edit', compact('employee', 'teams'));
  }

  public function update(UpdateRequest $request, Employee $employee)
  {
    $employee->update($request->validated());
    return to_route('admin.employees.index')->with('success', 'Employee updated successfully.');
  }

  public function destroy(Employee $employee)
  {
    $employee->delete();
    return to_route('admin.employees.index')->with('success', 'Employee deleted successfully.');
  }
}
