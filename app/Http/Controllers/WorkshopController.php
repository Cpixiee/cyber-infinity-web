<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkshopController extends Controller
{
    public function index()
    {
        $workshops = Workshop::latest()->get();
        return view('workshops.index', compact('workshops'));
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
            'activity_type' => 'required|string|in:workshop,bootcamp,training',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'target_participants' => 'required|integer|min:1',
            'requirements' => 'nullable|string'
        ]);

        $validated['status'] = 'upcoming';
        Workshop::create($validated);

        return redirect()->route('workshops.index')
            ->with('success', 'Workshop created successfully');
    }

    public function destroy(Workshop $workshop)
    {
        $workshop->delete();
        return redirect()->route('workshops.index')
            ->with('success', 'Workshop deleted successfully');
    }

    public function register(Workshop $workshop)
    {
        $user = Auth::user();

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
