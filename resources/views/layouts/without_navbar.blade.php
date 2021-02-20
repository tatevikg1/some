@extends('layouts.base')

@section('layout')

<div style="position:absolute; right:10px;">
    <a href="javascript:history.back()">
        <i class="fa fa-window-close" style="color:#fff"></i>
    </a>
</div>
<div>
    <main class="py-4 grey">
        @yield('content')
    </main>
</div>

@endsection

