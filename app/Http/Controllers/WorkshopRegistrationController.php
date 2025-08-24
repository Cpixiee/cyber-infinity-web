<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopRegistrationController extends Controller
{
    public function checkStatus(Request $request, Workshop $workshop)
    {
        $email = $request->input('email');
        $nis = $request->input('nis');

        $isRegistered = $workshop->isUserRegistered($email, $nis);
        
        return response()->json([
            'isRegistered' => $isRegistered
        ]);
    }
}
