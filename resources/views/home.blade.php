@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @foreach($files as $file)
                        <p>
                        <a href="/storage/{{ $file }}" target="_blank">
                            <span class="oi oi-file"></span>
                            <span>{{ $file }}</span>
                        </a>
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
