<?php

namespace App\Http\Controllers;

use App\User;
use App\UserFile;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Filters\Video\VideoFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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
        $files = Storage::disk()->files('user-files/' . Auth::id());
        $users = User::all();
        return view('home', compact('files', 'users'));
    }

    public function upload(Request $request)
    {
        $files = $request->file('files');
        $userId = $request->user_id;
        $errors = [];
        if($request->hasFile('files'))
        {
            foreach ($files as $file) {
                $baseFileName = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
                $baseFileName = explode('_', $baseFileName);
                if (count($baseFileName) !== 3 || strlen($baseFileName[0]) !== 8 || strlen($baseFileName[1]) !== 4 || strlen($baseFileName[2]) !== 4) {
                    $errors[] = 'File '. $file->getClientOriginalName() . ' did not saved, because it has no proper format. Filename should be in this format: XXXXXXXX_XXXX_XXXX';
                    continue;
                }
                $filename = $file->store('user-files/' . $userId);

                if ($file->getMimeType() === 'video/mp4') {
                    $type = 'video';
                    FFMpeg::fromDisk('s3')
                        ->open($filename)
                        ->addFilter(function (VideoFilters $filters) {
                            $filters->clip(
                                TimeCode::fromSeconds(0),
                                TimeCode::fromSeconds(config('laravel-ffmpeg.video_duration'))
                            );
                        })
                        ->export()
                        ->toDisk('s3')
                        ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
                        ->save('short/' . $filename);
                } else {
                    $type = 'image';
                }


                Storage::temporaryUrl($file, now()->addMinutes(5));
                UserFile::create([
                    'user_id'       => $userId,
                    'file'          => $filename,
                    'type'          => $type,
                    'short_version' => 'short/' . $filename,
                    'order_number'  => $baseFileName[0],
                    'product_code'  => $baseFileName[1],
                    'sequence'      => $baseFileName[2]
                ]);
            }
        }

        return redirect()->back()->withErrors($errors);
    }
}
