<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalTenants = class_exists(Tenant::class) ? Tenant::count() : 0;
        $totalUsers = class_exists(User::class) ? User::count() : 0;

        $latestTenants = class_exists(Tenant::class)
            ? Tenant::latest()->take(5)->get()
            : collect();

        $latestUsers = class_exists(User::class)
            ? User::latest()->take(5)->get()
            : collect();

        return view('admin.dashboard', compact(
            'totalTenants',
            'totalUsers',
            'latestTenants',
            'latestUsers'
        ));
    }
}