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

    // List all users
    public function index()
    {
        $users = $this->userService->getAllUsers();

        // Log activity for viewing user list
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => 'Melihat daftar pengguna',
            'properties' => null,
        ]);

        return view('users.index', compact('users'));
    }

    // Show the create user form
    public function create()
    {
        // Log activity for accessing create user form
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => 'Mengakses formulir tambah pengguna',
            'properties' => null,
        ]);

        return view('users.create');
    }

    // Store a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,warehouse_manager,warehouse_staff'
        ]);

        $user = $this->userService->createUser($validated);

        // Log activity for user creation
        if ($user) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'action' => "Menambahkan pengguna: {$user->name} dengan peran {$user->role}",
                'properties' => json_encode($validated),
            ]);
        }

        return $user 
            ? redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.')
            : redirect()->back()->with('error', 'Gagal menambahkan user.');
    }

    // Show the edit user form
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Log activity for accessing edit user form
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => "Mengakses formulir edit pengguna: {$user->name}",
            'properties' => json_encode(['user_id' => $user->id]),
        ]);

        return view('users.edit', compact('user'));
    }

    // Update user details
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,warehouse_manager,warehouse_staff'
        ]);

        $oldUser = User::findOrFail($id)->toArray();
        $user = $this->userService->updateUser($id, $validated);

        // Log activity for user update
        if ($user) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'action' => "Memperbarui pengguna: {$user->name}",
                'properties' => json_encode([
                    'before' => $oldUser,
                    'after' => $validated,
                ]),
            ]);
        }

        return $user 
            ? redirect()->route('users.index')->with('success', 'User berhasil diperbarui.')
            : redirect()->back()->with('error', 'Gagal memperbarui user.');
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $deleted = $this->userService->deleteUser($id);

        // Log activity for user deletion
        if ($deleted) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'action' => "Menghapus pengguna: {$user->name}",
                'properties' => json_encode($user->toArray()),
            ]);
        }

        return $deleted 
            ? redirect()->route('users.index')->with('success', 'User berhasil dihapus.')
            : redirect()->back()->with('error', 'Gagal menghapus user.');
    }

    // View activity logs for a specific user
    public function activity(User $user)
    {
        $activities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Log activity for viewing user activity logs
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => "Melihat aktivitas pengguna: {$user->name}",
            'properties' => json_encode(['viewed_user_id' => $user->id]),
        ]);

        return view('users.activity', compact('user', 'activities'));
    }
}
