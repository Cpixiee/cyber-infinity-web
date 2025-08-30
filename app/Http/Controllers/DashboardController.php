<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopRegistration;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalWorkshops = Workshop::where('status', 'active')->count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        
        // Active users this month (users who registered this month)
        $activeUsersThisMonth = User::where('role', '!=', 'admin')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Previous month active users for comparison
        $activeUsersPrevMonth = User::where('role', '!=', 'admin')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        // Calculate active users growth percentage
        $activeUsersGrowth = $activeUsersPrevMonth > 0 
            ? round((($activeUsersThisMonth - $activeUsersPrevMonth) / $activeUsersPrevMonth) * 100, 1)
            : 100;
        
        // Monthly registrations for this year
        $monthlyRegistrations = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyRegistrations[] = User::where('role', '!=', 'admin')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }
        
        // Workshop registrations this month
        $workshopRegistrationsThisMonth = WorkshopRegistration::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Users registered this month
        $usersRegisteredThisMonth = User::where('role', '!=', 'admin')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Active workshops this month (workshops that have sessions this month)
        $workshopsThisMonth = Workshop::where('status', 'active')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Latest workshops
        $workshops = Workshop::where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        // Recent registrations for display
        $recentRegistrations = WorkshopRegistration::with('workshop')
            ->latest()
            ->take(5)
            ->get();

        // Daily registration data for chart (last 7 days)
        $dailyLabels = [];
        $dailyValues = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('D'); // Mon, Tue, etc.
            $dailyValues[] = User::where('role', '!=', 'admin')
                ->whereDate('created_at', $date)
                ->count();
        }

        // Calculate performance percentage (example: based on user growth)
        $performancePercentage = $activeUsersPrevMonth > 0 
            ? min(100, max(0, (($activeUsersThisMonth / max($activeUsersPrevMonth, 1)) * 50))) 
            : 50;

        return view('dashboard', compact(
            'totalWorkshops', 
            'totalUsers', 
            'activeUsersThisMonth',
            'activeUsersGrowth',
            'monthlyRegistrations',
            'workshopRegistrationsThisMonth',
            'usersRegisteredThisMonth',
            'workshopsThisMonth',
            'workshops',
            'recentRegistrations',
            'dailyLabels',
            'dailyValues',
            'performancePercentage'
        ));
    }
}
