@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (Auth::user()->hasRole('administrator'))
                        <h4>Add user file</h4>
                        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>User</label>
                                <select name="user_id" class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name .' '. $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" name="files[]" multiple class="form-control-file">
                            </div>
                            <button class="btn btn-primary" type="submit">Send</button>
                        </form>
                    @else
{{--                        @foreach($files as $file)--}}
{{--                            <p>--}}
{{--                                <a href="{{ Storage::temporaryUrl($file, now()->addMinutes(5)) }}" target="_blank">--}}
{{--                                    <img src="{{ Storage::temporaryUrl($file, now()->addMinutes(5)) }}" />--}}
{{--                                </a>--}}
{{--                            </p>--}}
{{--                        @endforeach--}}
                    @endif
                    <image-viewer></image-viewer>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
