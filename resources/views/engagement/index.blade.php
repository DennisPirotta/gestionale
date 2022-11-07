<style>
    table{
        word-wrap:break-word;
    }
</style>
@extends('layouts.app')
@php($period = \Carbon\CarbonPeriod::create(\Carbon\Carbon::now()->firstOfMonth(),\Carbon\Carbon::now()->lastOfMonth()))
@section('content')
    <div class="container-fluid table-responsive">
        <table class="table text-center">
            <thead>
            <tr>
                <th scope="col">Dipendente</th>
                @foreach($period as $day)
                    <th scope="col">{{ $day->format('d') }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{ $user->name }} {{ $user->surname }}</th>
                @foreach($period as $day)
                    @if($user->hours->where('date',$day->format('Y-m-d'))->where('hour_type_id',6)->isEmpty())
                        @if($user->engagements->where('date',$day->format('Y-m-d'))->isEmpty())
                            <td></td>
                            @else
                            <td>
                                <span class="badge text-bg-primary">
                                    {{ $user->engagements->where('date',$day->format('Y-m-d'))->first()->order->innerCode }}
                                </span>
                            </td>
                        @endif
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