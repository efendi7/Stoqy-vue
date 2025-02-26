<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function create()
    {
        return view('users.create');
    }

    protected function logActivity($action, $description = '')
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Admin,Staff Gudang,Manajer Gudang,admin,manager', // Tidak perlu 'staff' karena sudah ada 'Staff Gudang'
        ]);

        // Memetakan role jika perlu
        $role = $this->mapRole($request->role);

        $user = $this->userService->createUser([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        $this->logActivity('create', 'Created new user: ' . $user->name);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:Admin,Staff Gudang,Manajer Gudang,admin,manager', // Tidak perlu 'staff' karena sudah ada 'Staff Gudang'
        ]);

        // Memetakan role jika perlu
        $role = $this->mapRole($request->role);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
        ];
        
        // Hanya update password jika disediakan
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $this->userService->updateUser($user->id, $userData);

        $this->logActivity('update', 'Updated user: ' . $user->name);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Memetakan nilai role dari form ke nilai yang akan disimpan di database
     */
    private function mapRole($role)
    {
        $roleMap = [
            'admin' => 'Admin',
            'manager' => 'Manajer Gudang',
            // Nilai-nilai yang sudah benar tidak perlu dipetakan
            'Admin' => 'Admin',
            'Manajer Gudang' => 'Manajer Gudang', 
            'Staff Gudang' => 'Staff Gudang'
        ];

        return $roleMap[$role] ?? $role;
    }

    public function destroy(User $user)
    {
        $userName = $user->name;
        $this->userService->deleteUser($user->id);
        
        $this->logActivity('delete', 'Deleted user: ' . $userName);
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
    }

    public function activity(User $user)
    {
        $activities = $user->activities()->latest()->paginate(10);
        return view('users.activity', compact('user', 'activities'));
    }

    public function allActivities()
    {
        $activities = ActivityLog::with('user')->latest()->paginate(15);
        return view('activity.logs', compact('activities'));
    }
}