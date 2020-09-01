@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading mb-3"></div>

                <div class="" id="app">
                    <chat-app :user="{{ Auth::user() }}"></chat-app>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
