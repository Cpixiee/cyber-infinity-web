<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WorkshopsController extends Controller
{
    public function index()
    {
        // Get all active workshops with their registration counts
        $workshops = Workshop::where('status', 'active')
            ->withCount(['registrations as approved_count' => function($query) {
                $query->where('status', 'approved');
            }])
            ->withCount(['registrations as pending_count' => function($query) {
                $query->where('status', 'pending');
            }])
            ->latest()
            ->get();
        
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        // For authenticated users, get their workshop registration status
        $userRegistration = null;
        if (Auth::check()) {
            $user = Auth::user();
            $userRegistration = \App\Models\WorkshopRegistration::where(function($query) use ($user) {
                $query->where('email', $user->email)
                    ->orWhere('nis', $user->nis);
            })
            ->with('workshop')
            ->first();
        }
        
        return view('workshops.index', compact('workshops', 'isAdmin', 'userRegistration'));
    }

    public function create()
    {
        return view('workshops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            'activity_type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
                'duration' => 'required|numeric|min:0.5|max:999.9',
                'location' => 'required|string',
                'target_participants' => 'required|integer|min:1',
                'requirements' => 'nullable|string',
                'status' => 'nullable|string|in:active,pending,completed,cancelled'
        ]);

        $validated['status'] = $validated['status'] ?? 'active';

        $workshop = Workshop::create($validated);

        // Create notification for all non-admin users about new workshop
        Notification::createWorkshopNotification(
            'workshop_new',
            'Workshop Baru Tersedia!',
            'Workshop baru "' . $workshop->title . '" telah tersedia. Daftar sekarang!',
            $workshop->id
        );

        return redirect()->route('workshops.index')
            ->with('success', 'Workshop berhasil dibuat');
    }

    public function destroy(Workshop $workshop)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('workshops.index')
                ->with('error', 'Unauthorized access');
        }

        $workshop->delete();

        return redirect()->route('workshops.index')
            ->with('success', 'Workshop deleted successfully');
    }

    public function edit(Workshop $workshop)
    {
        return view('workshops.edit', compact('workshop'));
    }

    public function update(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            'activity_type' => 'required|string|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'target_participants' => 'required|integer|min:1',
                'requirements' => 'nullable|string',
                'location' => 'required|string',
                'start_time' => 'required',
                'duration' => 'required|numeric|min:0.5',
                'status' => 'required|in:active,pending,completed,cancelled'
        ]);

        $workshop->update($validated);

        return redirect()->route('workshops.index')
            ->with('success', 'Workshop berhasil diperbarui');
    }

    public function register(Workshop $workshop)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user already registered
        $alreadyRegistered = $workshop->registrations()
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('workshops.index')
                ->with('error', 'You are already registered for this workshop');
        }

        $workshop->registrations()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        return redirect()->route('workshops.index')
            ->with('success', 'Registration successful');
    }
}
