<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'roles' => Role::query()->count(),
            'permissions' => Permission::query()->count(),
            'users' => User::query()->count(),
            'active_users' => User::query()->where('is_active', true)->count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
