@extends('layouts.base')

@section('layout')

<div class="close-button">
    <a href="javascript:history.back()">
        <i class="far fa-times-circle fa-3x white"></i>
    </a>
</div>
<div>
    <main class="py-4 grey">
        @yield('content')
    </main>
</div>

@endsection

