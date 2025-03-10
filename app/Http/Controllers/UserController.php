<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,warehouse_manager,warehouse_staff'
        ]);

        $user = $this->userService->createUser($validated);

        return $user ? redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.') 
                     : redirect()->back()->with('error', 'Gagal menambahkan user.');
    }

    public function create()
{
    return view('users.create');
}


    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('users.edit', compact('user'));
}


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,warehouse_manager,warehouse_staff'
        ]);

        $user = $this->userService->updateUser($id, $validated);

        return $user ? redirect()->route('users.index')->with('success', 'User berhasil diperbarui.') 
                     : redirect()->back()->with('error', 'Gagal memperbarui user.');
    }

    public function destroy($id)
    {
        $deleted = $this->userService->deleteUser($id);

        return $deleted ? redirect()->route('users.index')->with('success', 'User berhasil dihapus.') 
                        : redirect()->back()->with('error', 'Gagal menghapus user.');
    }

    public function activity(User $user)
    {
        $activities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        return view('users.activity', compact('user', 'activities'));
    }
    

}
