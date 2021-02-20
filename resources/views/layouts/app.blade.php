@extends('layouts.base')

@section('layout')

@include('partials.navbar')
<div style="margin-top:10px">
    <main class="py-4 mt-4">
        @yield('content')
    </main>
</div>

@endsection

