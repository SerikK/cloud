<?php

namespace App\Http\Controllers;

use App\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function files()
    {
        // $files = Storage::disk()->files('user-files/' . Auth::id());
        $files = UserFile::where('user_id', Auth::id())->get();
        foreach ($files as $file) {
            $file->file = Storage::temporaryUrl($file->file, now()->addMinutes(5));
            if ($file->type === 'video') {
                $file->short_version = Storage::temporaryUrl($file->short_version, now()->addMinutes(5));
            }
        }
        return $files;
    }

    public function customerCreated(Request $request)
    {
        $content = json_decode($request->getContent());
        Log::debug('Bigcommerce request: ' . print_r($content, 1));
//        $userId = $content->data->id;
//        Storage::disk('s3')->makeDirectory('user-files/' . $userId);
    }
}
