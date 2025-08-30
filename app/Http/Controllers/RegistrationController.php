<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use App\Models\WorkshopRegistration;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index()
    {
        $workshops = Workshop::with(['registrations' => function ($query) {
            $query->latest();
        }])->get();

        return view('admin.registrations.index', compact('workshops'));
    }

    public function store(Request $request, Workshop $workshop)
    {
        try {
            Log::info('Registration attempt received', $request->all());

            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'class' => 'required|string|max:50',  // Increased max length
                'nis' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'agreement_1' => 'required|string|in:on',
                'agreement_2' => 'required|string|in:on',
                'agreement_3' => 'required|string|in:on',
            ]);

            if ($validator->fails()) {
                Log::warning('Registration validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dimasukkan tidak valid.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Check if user already registered in the current workshop
            $existingRegistration = $workshop->registrations()
                ->where(function($query) use ($validated) {
                    $query->where('email', $validated['email'])
                          ->orWhere('nis', $validated['nis']);
                })->first();

            // Check if user already registered in any workshop
            $anyWorkshopRegistration = WorkshopRegistration::where(function($query) use ($validated) {
                    $query->where('email', $validated['email'])
                          ->orWhere('nis', $validated['nis']);
                })->first();

            if ($existingRegistration) {
                Log::info('Duplicate registration attempt for same workshop', [
                    'email' => $validated['email'],
                    'nis' => $validated['nis'],
                    'workshop_id' => $workshop->id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar di workshop ini.'
                ], 422);
            }

            if ($anyWorkshopRegistration) {
                Log::info('Duplicate registration attempt for different workshop', [
                    'email' => $validated['email'],
                    'nis' => $validated['nis'],
                    'workshop_id' => $workshop->id,
                    'existing_workshop_id' => $anyWorkshopRegistration->workshop_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar di workshop lain. Satu peserta hanya boleh mendaftar di satu workshop.'
                ], 422);
            }

            try {
                $registration = new \App\Models\WorkshopRegistration();
                $registration->full_name = $validated['full_name'];
                $registration->class = $validated['class'];
                $registration->nis = $validated['nis'];
                $registration->email = $validated['email'];
                $registration->status = 'pending';
                $registration->workshop_id = $workshop->id;
                $registration->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.',
                    'redirect' => route('workshops.index')
                ]);

            } catch (\Exception $e) {
                Log::error('Registration save error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah terdaftar di workshop lain. Satu peserta hanya boleh mendaftar di satu workshop.'
                ], 422);
            }

            Log::info('Registration successful', [
                'registration_id' => $registration->id,
                'workshop_id' => $workshop->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.',
                'redirect' => route('workshops.index')
            ]);

        } catch (\Exception $e) {
            Log::error('Registration error occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function approve(WorkshopRegistration $registration)
    {
        $registration->update(['status' => 'approved']);
        
        // Find user by email and create notification
        $user = User::where('email', $registration->email)->first();
        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'workshop_status_change',
                'title' => 'Pendaftaran Workshop Disetujui!',
                'message' => 'Pendaftaran Anda untuk workshop "' . $registration->workshop->title . '" telah disetujui.',
                'data' => [
                    'workshop_id' => $registration->workshop_id,
                    'registration_id' => $registration->id,
                    'status' => 'approved'
                ]
            ]);
        }
        
        return redirect()->back()->with('success', 'Pendaftaran berhasil disetujui.');
    }

    public function reject(WorkshopRegistration $registration)
    {
        $registration->update(['status' => 'rejected']);
        
        // Find user by email and create notification
        $user = User::where('email', $registration->email)->first();
        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'workshop_status_change',
                'title' => 'Pendaftaran Workshop Ditolak',
                'message' => 'Pendaftaran Anda untuk workshop "' . $registration->workshop->title . '" telah ditolak.',
                'data' => [
                    'workshop_id' => $registration->workshop_id,
                    'registration_id' => $registration->id,
                    'status' => 'rejected'
                ]
            ]);
        }
        
        return redirect()->back()->with('success', 'Pendaftaran berhasil ditolak.');
    }
}
