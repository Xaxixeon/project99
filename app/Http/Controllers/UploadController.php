<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store (Request $request)
    {
        $request->validate([
            'design' => 'required|file|mimes:jpg,jpeg,png,pdf,zip,psd,ai,cdr|max:102400'
        ]);

        $path = $request->file('design')->store('designs', 'public');

        // save to file_manager table (optional)
        // \DB::table('file_manager')->insert([...]);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url'  => Storage::url($path),
        ]);
    }
}
