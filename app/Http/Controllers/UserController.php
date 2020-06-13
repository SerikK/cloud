<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function files()
    {
        $files = Storage::disk()->files('user-files/' . Auth::id());
        $awsFiles = [];
        foreach ($files as $file) {
            $awsFiles[] = Storage::temporaryUrl($file, now()->addMinutes(5));
        }
        return $awsFiles;
    }

    public function customerCreated(Request $request)
    {
        $content = json_decode($request->getContent());
        Log::debug('Bigcommerce request: ' . print_r($content, 1));
        $userId = $content->data->id;
        Storage::disk('s3')->makeDirectory('user-files/' . $userId);
    }
}
