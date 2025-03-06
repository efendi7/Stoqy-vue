<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = ActivityLog::with('user')
            ->when($request->search, function($query, $search) {
                return $query->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('activities.index', compact('activities'));
    }

    public function userActivities($userId)
    {
        $activities = ActivityLog::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('activities.index', compact('activities'));
    }
}