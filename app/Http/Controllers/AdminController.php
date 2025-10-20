<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = User::all(); // Или ->paginate() если много
        return view('admin.users', compact('users'));
    }
    public function updateRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя изменить свои права.');
        }

        $request->validate([
            'role' => ['required', 'in:user,moderator,admin'],
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Роль пользователя обновлена.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя удалить самого себя!');
        }

        $user->delete();
        return back()->with('success', 'Пользователь удалён.');
    }

    
}
