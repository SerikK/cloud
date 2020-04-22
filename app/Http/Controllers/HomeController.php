<?php

namespace App\Http\Controllers;

use App\User;
use App\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $files = Storage::disk('public')->files('user-files/' . Auth::id());
        $users = User::all();
        return view('home', compact('files', 'users'));
    }

    public function upload(Request $request)
    {
        $files = $request->file('files');
        $userId = $request->user_id;

        if($request->hasFile('files'))
        {
            foreach ($files as $file) {
                $filename = $file->store('user-files/' . $userId .'/', ['disk' => 'public']);
                UserFile::create([
                    'user_id' => $userId,
                    'file' => $filename
                ]);
            }
        }

        return redirect()->back();
    }
}
