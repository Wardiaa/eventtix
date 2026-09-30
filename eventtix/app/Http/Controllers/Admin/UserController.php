<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount(['events', 'bookings'])
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => ['required', 'in:admin,organizer,user']]);

        abort_if($user->id === $request->user()->id, 422, 'Vous ne pouvez pas modifier votre propre rôle.');

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Rôle mis à jour pour '.$user->name.'.');
    }
}
