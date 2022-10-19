@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
    <div class="container my-5 p-5 shadow-sm">
        <div class="d-flex align-items-center">
            <span class="m-0 h1">Inserisci una nuova commessa</span>
        </div>
        <hr>
        <form method="post" action="/commesse" class="row mt-4">
            @csrf
            <div class="col-md-4 col-sm-6">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-building me-2"></i>Compagnia</label>
                    <select class="form-select" id="inputGroupSelect01" name="company_id">
                        @foreach($companies as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('company')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-lightning-charge me-2"></i>Stato</label>
                    <select class="form-select" id="inputGroupSelect01" name="status_id">
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->description}}</option>
                        @endforeach
                    </select>
                    @error('status')
                    <p class="text-danger fs-6">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i
                                class="bi bi-globe2 me-2"></i>Paese</label>
                    <select class="form-select" id="inputGroupSelect01" name="country_id">
                        @foreach($countries as $country)
                            <option value="{{$country->id}}"
                                    @if($country->name === "Italy") selected @endif
                                    data-thumbnail="{{ asset('images/flags/' . strtolower($country->code)) }}.svg"
                                    >
                                {{ __($country->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('country')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Descrizione</span>
                    <input type="text" class="form-control" aria-label="Descrizione" name="description"
                           value="{{ old('description') }}">
                </div>
                @error('description')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SW</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSW">
                </div>
                @error('hourSW')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore MS</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourMS"
                           value="{{ old('hourMS') }}">
                </div>
                @error('hourMS')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore FAT</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourFAT"
                           value="{{ old('hourFAT') }}">
                </div>
                @error('hourFAT')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SAF</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSAF"
                           value="{{ old('hourSAF') }}">
                </div>
                @error('hourSAF')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i
                                class="bi bi-person me-2"></i>Progresso</label>
                    <select class="form-select" id="inputGroupSelect01" name="job_type_id">
                        @unless(count($job_types) === 0)
                            @foreach($job_types as $job_type)
                                <option value="{{$job_type->id}}">{{$job_type->description}}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>
                @error('progress')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-check me-2"></i>Apertura</span>
                    <input type="date" class="form-control" aria-label="Apertura" name="opening"
                           value="{{ Carbon::now()->format('Y-m-d') }}">
                </div>
                @error('opening')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-x me-2"></i>Chiusura</span>
                    <input type="date" class="form-control" aria-label="Chiusura" name="closing"
                           value="{{ old('closing') }}">
                </div>
                @error('closing')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i
                                class="bi bi-person me-2"></i>Cliente</label>
                    <select class="form-select" id="inputGroupSelect01" name="customer_id">
                        @unless(count($customers) === 0)
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>
                @error('customer_id')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i
                                class="bi bi-person me-2"></i>Responsabile</label>
                    <select class="form-select" id="inputGroupSelect01" name="user_id">
                        @unless(count($users) === 0)
                            @foreach($users as $user)
                                <option value="{{$user->id}}" @if($user->id === auth()->id()) selected @endif >{{$user->name}} {{$user->surname}}</option>
                            @endforeach
                        @endunless
                    </select>
                </div>
                @error('user_id')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Salva</button>
        </form>
    </div>

@endsection
