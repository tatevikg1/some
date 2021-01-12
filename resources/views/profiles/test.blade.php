@extends('layouts.app')

@section('title', 'Profile')

@section('content')
@foreach($returncontacts as $user)
<div>{{ $user->username }}</div>
                <div class="col-3">
{{ $user->unread }}

                </div>
@endforeach

@endsection
