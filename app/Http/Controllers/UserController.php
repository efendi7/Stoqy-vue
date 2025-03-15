<?php
namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Menampilkan daftar pengguna
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', compact('users'));
    }

    // Menampilkan form tambah pengguna
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $user = $this->userService->createUser($request->all());

        return $user 
            ? redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.')
            : redirect()->back()->with('error', 'Gagal menambahkan user.');
    }

    // Menampilkan form edit pengguna
    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.edit', compact('user'));
    }

    // Memperbarui data pengguna
    public function update(Request $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->all());

        return $user 
            ? redirect()->route('users.index')->with('success', 'User berhasil diperbarui.')
            : redirect()->back()->with('error', 'Gagal memperbarui user.');
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $deleted = $this->userService->deleteUser($id);

        return $deleted 
            ? redirect()->route('users.index')->with('success', 'User berhasil dihapus.')
            : redirect()->back()->with('error', 'Gagal menghapus user.');
    }

    // Menampilkan log aktivitas pengguna
    public function activity($id)
    {
        $user = $this->userService->getUserById($id);
        $activities = $this->userService->getUserActivities($id);

        return view('users.activity', compact('user', 'activities'));
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
