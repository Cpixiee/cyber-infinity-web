<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\WorkshopRegistration;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWorkshops = Workshop::count();
        $totalRegistrations = WorkshopRegistration::count();
        $pendingRegistrations = WorkshopRegistration::where('status', 'pending')->count();
        $totalUsers = User::count();

        return view('admin.dashboard', compact(
            'totalWorkshops',
            'totalRegistrations',
            'pendingRegistrations',
            'totalUsers'
        ));
    }
}
