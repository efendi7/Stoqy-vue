<?php
namespace App\Services;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserService
{
    public function getAllUsers()
    {
        $users = User::paginate(10);
        foreach ($users as $user) {
            $user->role_label = $this->getRoleLabel($user->role);
        }
        return $users;
    }

    public function getUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function createUser(array $data)
{
    $validated = $this->validateUserData($data);

    try {
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'pending'; // Set role default ke "pending"
        $user = User::create($validated);

        $this->logActivity("Pengguna baru terdaftar: {$user->name}, menunggu verifikasi role", $user->toArray());

        Log::info("User berhasil dibuat dan menunggu verifikasi: ", ['id' => $user->id, 'email' => $user->email]);
        return $user;
    } catch (Exception $e) {
        Log::error("Gagal membuat user: " . $e->getMessage());
        return null;
    }
}

    public function updateUser($id, array $data)
    {
        $validated = $this->validateUserData($data, $id);

        try {
            $user = $this->getUserById($id);
            $oldUserData = $user->toArray();

            if (isset($validated['password']) && !empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            $this->logActivity("Memperbarui pengguna: {$user->name}", [
                'before' => $oldUserData,
                'after' => $validated,
            ]);

            Log::info("User berhasil diperbarui: ", ['id' => $user->id, 'email' => $user->email]);
            return $user;
        } catch (ModelNotFoundException $e) {
            Log::error("User dengan ID {$id} tidak ditemukan!");
            return null;
        } catch (Exception $e) {
            Log::error("Gagal memperbarui user: " . $e->getMessage());
            return null;
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = $this->getUserById($id);

            if (auth()->id() === $user->id) {
                Log::warning("User mencoba menghapus dirinya sendiri: ID {$id}");
                return false;
            }

            $deleted = $user->delete();
            $this->logActivity("Menghapus pengguna: {$user->name}", $user->toArray());

            Log::info("User berhasil dihapus:", ['id' => $user->id, 'name' => $user->name]);
            return $deleted;
        } catch (ModelNotFoundException $e) {
            Log::error("User dengan ID {$id} tidak ditemukan!");
            return false;
        } catch (Exception $e) {
            Log::error("Gagal menghapus user: " . $e->getMessage());
            return false;
        }
    }

    public function getUserActivities(int $userId)
    {
        return ActivityLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    private function validateUserData(array $data, int $userId = null): array
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($userId ?? 'NULL'),
            'password' => $userId ? 'nullable|min:8' : 'required|min:8',
            'role' => 'required|in:admin,warehouse_manager,warehouse_staff'
        ])->validate();
    }

    private function logActivity(string $action, $properties = null): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => $action,
            'properties' => json_encode($properties),
        ]);
    }

    public function getRoleLabel($role)
    {
        $roleLabels = [
            'admin' => 'Admin',
            'warehouse_manager' => 'Manajer Gudang',
            'warehouse_staff' => 'Staff Gudang',
        ];

        return $roleLabels[$role] ?? 'Unknown';
    }

    public function getUserRole($userId)
    {
        $user = User::find($userId);
        return $user ? $user->role : null;
    }

    public function getAvailableRoles()
    {
        return ['admin', 'warehouse_manager', 'warehouse_staff']; 
    }

    public function requestRoleChange($userId, $requestedRole)
{
    $user = $this->getUserById($userId);

    if (!$user || $user->role !== 'pending') {
        return false; // Hanya user "pending" yang bisa mengajukan role
    }

    $allowedRoles = ['warehouse_manager', 'warehouse_staff'];
    if (!in_array($requestedRole, $allowedRoles)) {
        return false; // Pastikan hanya role yang diperbolehkan
    }

    $user->requested_role = $requestedRole;
    $user->save();

    $this->logActivity("Pengguna {$user->name} mengajukan role: $requestedRole", $user->toArray());

    return true;
}

public function approveRoleChange($userId, $approvedRole)
{
    $user = $this->getUserById($userId);

    if (!$user || !$user->requested_role) {
        return false; // Hanya user yang mengajukan role yang bisa disetujui
    }

    if ($approvedRole !== $user->requested_role) {
        return false; // Role yang disetujui harus sesuai dengan yang diajukan
    }

    $user->role = $approvedRole;
    $user->requested_role = null;
    $user->save();

    $this->logActivity("Admin menyetujui role {$approvedRole} untuk pengguna: {$user->name}", $user->toArray());

    return true;
}


    
}
