@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
@section('content')
    <div class="container shadow-sm p-5 mt-5">
        <div class="d-flex align-items-center">
            <span class="m-0 h1">{{ $user->name }} {{ $user->surname }}</span>
            @role('admin|boss')
            <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#permissions"><i
                        class="bi bi-pen me-2"></i>Modifica
            </button>
            @endrole
        </div>
        @if($user->hasRole('user'))
        <span class="badge text-bg-info rounded-pill mt-2 fs-6">Dipendente</span>
        @endif
        @if($user->hasRole('admin'))
            <span class="badge text-bg-success rounded-pill ms-2 fs-6">Amministrazione</span>
        @endif
        @if($user->hasRole('developer'))
            <span class="badge text-bg-warning rounded-pill ms-2 fs-6">Sviluppatore</span>
        @endif
        @if($user->hasRole('boss'))
            <span class="badge text-bg-danger rounded-pill ms-2 fs-6">Direzione</span>
        @endif
        <hr>
        <h5>{{ $user->email }}</h5>
        <span class="badge @if($user->company->id === 1) text-bg-primary @else text-bg-success @endif fs-6">{{ $user->company->name }}</span>
    </div>
    <div class="container table-responsive mt-5 shadow-sm p-5">
        <div class="d-flex align-items-center">
            <span class="m-0 h1">Orario di lavoro</span>
            <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#workingDaysModal"><i
                        class="bi bi-pen me-2"></i>Modifica
            </button>
        </div>
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
    <div class="container shadow-sm p-5 mt-5">
        <div class="d-flex align-items-center">
            <span class="m-0 h1">Ferie</span>
            <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#LeftHolidaysModal"><i
                        class="bi bi-pen me-2"></i>Modifica
            </button>
        </div>
        <hr>
        <div class="row align-items-center d-flex fs-3">
            <div class="col col-md-3">
                <span class="h5 m-0">Ore Totali</span>
            </div>
            <div class="col col-md-9">
                <span class="badge text-bg-primary m-0">{{ $user->holidays }}</span>
            </div>
        </div>
        <div class="row align-items-center d-flex fs-3">
            <div class="col col-md-3">
                <span class="h5 m-0">Ore Restanti</span>
            </div>
            <div class="col col-md-9">
                <span class="badge text-bg-primary m-0">{{ $user->getLeftHolidays() }}</span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="workingDaysModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modifica Orario di lavoro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('users.time.update',$user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body table-responsive">

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
                                    <th scope="row" class="align-middle">
                                        {{ __($hour->week_day) }}
                                    </th>
                                    <td>
                                        <label>
                                            <input class="form-control" type="time"
                                                   name="days[{{ $hour->week_day }}][morning_start]"
                                                   value="{{ Carbon::parse($hour->morning_start)->format('H:i') }}">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input class="form-control" type="time"
                                                   name="days[{{ $hour->week_day }}][morning_end]"
                                                   value="{{ Carbon::parse($hour->morning_end)->format('H:i') }}">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input class="form-control" type="time"
                                                   name="days[{{ $hour->week_day }}][afternoon_start]"
                                                   value="{{ Carbon::parse($hour->afternoon_start)->format('H:i') }}">
                                        </label>
                                    </td>
                                    <td>
                                        <label>
                                            <input class="form-control" type="time"
                                                   name="days[{{ $hour->week_day }}][afternoon_end]"
                                                   value="{{ Carbon::parse($hour->afternoon_end)->format('H:i') }}">
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="LeftHolidaysModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifica Ferie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('users.holidays.update',$user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <label for="holidays" class="d-none"></label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-clock me-2"></i> Ore di ferie</span>
                            <input type="number" class="form-control" value="{{ $user->holidays }}" id="holidays"
                                   name="holidays">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @role('admin|boss')
    <!-- Modal -->
    <div class="modal fade" id="permissions" tabindex="-1" aria-labelledby="permissionsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="permissionsLabel">Modifica Permessi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('users.permissions.update',$user->id) }}">
                    @csrf
                    <div class="modal-body">
                        <p>Permessi attuali</p>
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="user">Dipendente</label>
                            <input class="form-check-input" type="checkbox" role="switch" id="user" name="user" @if($user->hasRole('user')) checked @endif>
                        </div>
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="admin">Amministratore</label>
                            <input class="form-check-input" type="checkbox" role="switch" id="admin" name="admin" @if($user->hasRole('admin')) checked @endif>
                        </div>
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="developer">Sviluppatore</label>
                            <input class="form-check-input" type="checkbox" role="switch" id="developer" name="developer" @if($user->hasRole('developer')) checked @endif>
                        </div>
                        @role('boss')
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="boss">Direttore</label>
                            <input class="form-check-input" type="checkbox" role="switch" id="boss" name="boss" @if($user->hasRole('boss')) checked @endif>
                        </div>
                        @endrole
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @endrole
@endsection