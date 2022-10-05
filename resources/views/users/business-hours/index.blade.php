@php
    use Carbon\Carbon;
    if (auth()->user()->level < 1){
        header('Location: ' . route('home.index'));
        die();
    }
@endphp
@extends('layouts.app')
@section('content')
    <div class="container table-responsive mt-5">
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
            @foreach($hours as $hour)
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