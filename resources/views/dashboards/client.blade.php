@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('notification-bell')
    @include('partials.notification_bell')
@endsection

@section('content')
    <div class="dashboard-header">
        <h1>Bienvenue, {{ Auth::user()->name }}</h1>
    </div>
    {{-- Add client dashboard content here --}}
@endsection