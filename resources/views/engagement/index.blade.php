@php
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
    $period = CarbonPeriod::create(Carbon::now()->firstOfMonth(),Carbon::now()->lastOfMonth())
@endphp
@extends('layouts.app')
@section('content')
    <div class="container-fluid table-responsive">
        <table class="table table-bordered text-center">
            <thead>
            <tr>
                <th scope="col" class="text-start">Dipendente</th>
                @foreach($period as $day)
                    <th scope="col">{{ $day->format('j') }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row" class="text-start">{{ $user->name }} {{ $user->surname }}</th>
                    @foreach($period as $day)
                        @if($user->hours->where('date',$day->format('Y-m-d'))->where('hour_type_id',6)->isEmpty())
{{--                            @if($user->engagements->where('date',$day->format('Y-m-d'))->where('hour_type_id',6)->isEmpty())--}}
{{--                                --}}
{{--                            @endif--}}
                            <td></td>
                        @else
                            <td><span class="badge text-bg-primary">Ferie</span></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection