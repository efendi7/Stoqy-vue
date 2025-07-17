<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): Response
    {
        $filters = $request->only('search');
        $users = $this->userService->getPaginatedUsers($filters);

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());
        return to_route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());
        return to_route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return to_route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Mengambil data aktivitas pengguna untuk modal.
     */
    public function activity(Request $request, User $user)
    {
        // Ambil data aktivitas dari service
        $activities = $this->userService->getUserActivities($user->id, 15);

        // PERBAIKAN: Hanya kembalikan data 'activities' sebagai prop partial
        // Ini akan mencegah error "Page not found"
        return Inertia::render('Users/Index', [
            'activities' => $activities,
        ], ['only' => ['activities']]);
    }

    // Method create() dan edit() tidak lagi diperlukan untuk menampilkan view
    // karena sudah ditangani oleh modal di frontend.
    public function create()
    {
        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return redirect()->route('users.index');
    }

    // Menampilkan halaman pengajuan role untuk user
    public function showRequestRolePage()
    {
        if (Auth::user()->role !== 'pending') {
            return redirect('/home')->with('error', 'Anda sudah memiliki role.');
        }
        return view('auth.request-role');
    }

    // User mengajukan role
    public function requestRole(Request $request)
    {
        $userId = Auth::id();
        $requestedRole = $request->input('requested_role');

        if ($this->userService->requestRoleChange($userId, $requestedRole)) {
            return redirect()->back()->with('success', 'Permintaan role telah diajukan. Tunggu persetujuan admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengajukan role.');
    }

    // Admin melihat daftar pengajuan role
    public function showRoleRequests()
    {
        $users = User::whereNotNull('requested_role')->get();
        return view('admin.role-requests', compact('users'));
    }

    // Admin menyetujui role yang diajukan
    public function approveRole(Request $request, $id)
    {
        $approvedRole = $request->input('approved_role');

        if ($this->userService->approveRoleChange($id, $approvedRole)) {
            return redirect()->back()->with('success', 'Role berhasil disetujui.');
        }

        return redirect()->back()->with('error', 'Gagal menyetujui role.');
    }

    // Admin menolak pengajuan role
    public function rejectRole($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->requested_role = null;
            $user->save();
            return redirect()->back()->with('success', 'Permintaan role ditolak.');
        }

        return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

}
