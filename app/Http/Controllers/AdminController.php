<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function roleRequests()
    {
        $users = User::whereNotNull('requested_role')->paginate(10);
        return view('admin.role-requests', compact('users'));
    }

    public function approveRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->approved_role;
        $user->requested_role = null; // Reset permintaan
        $user->save();

        return redirect()->route('admin.role-requests')->with('success', 'Role disetujui.');
    }

    public function rejectRole($id)
    {
        $user = User::findOrFail($id);
        $user->requested_role = null; // Reset permintaan
        $user->save();

        return redirect()->route('admin.role-requests')->with('success', 'Role ditolak.');
    }
}
