@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
    <div class="dashboard-header">
        <h1>Bienvenue, {{ Auth::user()->name }}</h1>
    </div>
    {{-- Add employee dashboard content here --}}
@endsection