<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(array $data)
    {
        // Logic to create a user
        return User::create($data);
    }

    public function updateUser($id, array $data)
    {
        // Logic to update a user
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser($id)
    {
        // Logic to delete a user
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function getUserRole($userId)
    {
        $user = User::find($userId);
        return $user ? $user->role : null;
    }

    public function isAdmin($userId)
    {
        return $this->getUserRole($userId) === 'Admin';
    }

    public function isManagerGudang($userId)
    {
        return $this->getUserRole($userId) === 'Manajer Gudang';
    }

    public function isStaffGudang($userId)
    {
        return $this->getUserRole($userId) === 'Staff Gudang';
    }

    public function getAllUsers()
    {
        return User::all();
    }
}
