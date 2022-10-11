@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('content')
    <div class="container shadow-sm p-5 mt-5">
        <h1>{{ $user->name }} {{ $user->surname }}</h1>
        <hr>
        <h5>{{ $user->email }}</h5>
        <span class="badge @if($user->company->id === 1) text-bg-primary @else text-bg-success @endif fs-6">{{ $user->company->name }}</span>
    </div>
    <div class="container table-responsive mt-5 shadow-sm p-5">
        <h1>Orario di lavoro</h1>
        @role('admin|boss')
            <button class="btn btn-primary"><i class="bi bi-pen me-2"></i>Modifica</button>
        @endrole
        <hr>
        <table class="table text-center table-striped">
            <thead>
            <tr>
                <th scope="col">Giorno</th>
                <th scope="col">Inizio mattina</th>
                <th scope="col">Fine mattina</th>
                <th scope="col">Inizio pomeriggio</th>
                <th scope="col">Fine pomeriggio</th>
            </tr>
            </thead>
            <tbody>
            @foreach($user->business_hours as $hour)
                <tr>
                    <th scope="row">{{ __($hour->week_day) }}</th>
                    <td>{{ Carbon::parse($hour->morning_start)->format('H:i') }}</td>
                    <td>{{ Carbon::parse($hour->morning_end)->format('H:i') }}</td>
                    <td>{{ Carbon::parse($hour->afternoon_start)->format('H:i') }}</td>
                    <td>{{ Carbon::parse($hour->afternoon_end)->format('H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection