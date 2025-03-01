<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserService
{
    public function createUser(array $data)
    {
        try {
            // Hash password jika ada
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = User::create($data);

            Log::info("User berhasil dibuat: ", ['id' => $user->id, 'email' => $user->email]);
            return $user;
        } catch (Exception $e) {
            Log::error("Gagal membuat user: " . $e->getMessage());
            return null;
        }
    }

    public function updateUser($id, array $data)
    {
        try {
            $user = User::findOrFail($id);

            // Hash password jika diperbarui
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // Hindari menghapus password jika tidak diperbarui
            }

            $user->update($data);

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
            $user = User::findOrFail($id);

            if (auth()->id() === $user->id) {
                Log::warning("User mencoba menghapus dirinya sendiri: ID {$id}");
                return false;
            }

            Log::info("Menghapus user:", ['id' => $user->id, 'name' => $user->name, 'role' => $user->role]);

            return $user->delete();
        } catch (ModelNotFoundException $e) {
            Log::error("User dengan ID {$id} tidak ditemukan!");
            return false;
        } catch (Exception $e) {
            Log::error("Gagal menghapus user: " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->role_label = $this->getRoleLabel($user->role);
        }
        return $users;
    }

    public function getRoleLabel($role)
    {
        $roleLabels = [
            'admin' => 'Admin',
            'warehouse_manager' => 'Manajer',
            'warehouse_staff' => 'Staff',
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
}
