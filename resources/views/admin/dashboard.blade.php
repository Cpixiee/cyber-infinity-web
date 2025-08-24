@extends('layouts.master')

@section('title', 'Admin Dashboard - Cyber Infinity')

@section('content')
<style>
    .admin-dashboard {
        padding: 2rem 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(0, 255, 0, 0.1);
        border: 1px solid #00ff00;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #00ff00;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #fff;
        font-size: 1.1rem;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-button {
        display: inline-block;
        padding: 1rem;
        background: transparent;
        border: 1px solid #00ffff;
        color: #00ffff;
        text-align: center;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .action-button:hover {
        background: #00ffff;
        color: #000;
    }
</style>

<div class="container">
    <div class="admin-dashboard">
        <h1 class="mb-4" style="color: #00ff00; margin-bottom: 2rem;">Admin Dashboard</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalWorkshops }}</div>
                <div class="stat-label">Total Workshops</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $totalRegistrations }}</div>
                <div class="stat-label">Total Registrations</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $pendingRegistrations }}</div>
                <div class="stat-label">Pending Registrations</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>

        <h2 style="color: #00ffff; margin-bottom: 1rem;">Quick Actions</h2>
        <div class="quick-actions">
            <a href="{{ route('workshops.index') }}" class="action-button">Manage Workshops</a>
            <a href="{{ route('admin.registrations.index') }}" class="action-button">Manage Registrations</a>
            <a href="{{ route('workshops.create') }}" class="action-button">Create New Workshop</a>
        </div>
    </div>
</div>
@endsection
