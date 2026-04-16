@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Utama</h2>
        <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong>!</p>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3 mb-3">
                <h5>Total Komik</h5>
                <h2>{{ \App\Models\Comic::count() }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3 mb-3">
                <h5>Total Genre</h5>
                <h2>{{ \App\Models\Genre::count() }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3 mb-3">
                <h5>Total User</h5>
                <h2>{{ \App\Models\User::count() }}</h2>
            </div>
        </div>
    </div>
@endsection
