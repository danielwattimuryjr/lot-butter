<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\Team\CreateRequest;
use App\Http\Requests\Team\UpdateRequest;

class TeamController extends Controller
{
  public function index(Request $request)
  {
    $limit = $request->input('limit', 10);

    $teams = Team::query()
      ->when(
        $request->input('name'),
        fn($query, $name) => $query->where('name', 'like', "%{$name}%"),
      )
      ->paginate($request->input('limit', $limit))
      ->withQueryString();

    return view('teams.index', compact('teams'));
  }

  public function create()
  {
    return view('teams.create');
  }

  public function store(CreateRequest $request)
  {
    $validated = $request->validated();

    $team = Team::create($validated);

    return to_route('admin.teams.index')
      ->with('success', 'Team created successfully.');
  }

  public function edit(Team $team)
  {
    return view('teams.edit', compact('team'));
  }

  public function update(UpdateRequest $request, Team $team)
  {
    $team->update($request->validated());

    return to_route('admin.teams.index')
      ->with('success', 'Team updated successfully.');
  }

  public function destroy(Team $team)
  {
    $team->delete();

    return to_route('admin.teams.index')
      ->with('success', 'Team deleted successfully.');
  }
}
