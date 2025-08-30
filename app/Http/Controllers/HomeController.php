<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function showForm()
    {
        return view('form');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'notelp' => 'required|string',
            'program' => 'required|in:basic,advanced,expert',
            'alasan' => 'required|string'
        ]);

        // Simpan data form (bisa ditambahkan nanti)
        
        return redirect()->back()->with('success', 'Pendaftaran berhasil dikirim!');
    }
}