@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
    <div class="container my-5 p-4 shadow-sm">
        <form method="POST" action="{{ route('orders.update',$commessa->id) }}" class="row"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-123 me-2"></i>Codice Interno</span>
                    <input type="text" class="form-control" aria-label="Codice Interno" name="innerCode"
                           value="{{ $commessa->innerCode }}">
                </div>
                @error('innerCode')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-123 me-2"></i>Codice Esterno</span>
                    <input type="text" class="form-control" aria-label="Codice Esterno" name="outerCode"
                           value="{{ $commessa->outerCode }}">
                </div>
                @error('outerCode')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-building me-2"></i>Compagnia</label>
                    <select class="form-select" id="inputGroupSelect01" name="company_id">
                        @foreach($companies as $company)
                            @if($company->id === $commessa->company->id)
                                <option value="{{$company->id}}" selected>{{$company->name}}</option>
                            @else
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @error('company_id')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-lightning-charge me-2"></i>Stato</label>
                    <select class="form-select" id="inputGroupSelect01" name="status_id">
                        @foreach($statuses as $status)
                            @if($status->id === $commessa->status->id)
                                <option value="{{$status->id}}" selected>{{$status->description}}</option>
                            @else
                                <option value="{{$status->id}}">{{$status->description}}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('status_id')
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
                            @if($country->id === $commessa->country->id)
                                <option value="{{$country->id}}" selected>{{$country->name}}</option>
                            @else
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @error('country_id')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Descrizione</span>
                    <input type="text" class="form-control" aria-label="Descrizione" name="description"
                           value="{{$commessa->description}}">
                </div>
                @error('description')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SW</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSW"
                           value="{{$commessa->hourSW}}">
                </div>
                @error('hourSW')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore MS</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourMS"
                           value="{{$commessa->hourMS}}">
                </div>
                @error('hourMS')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore FAT</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourFAT"
                           value="{{$commessa->hourFAT}}">
                </div>
                @error('hourFAT')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SAF</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSAF"
                           value="{{$commessa->hourSAF}}">
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
                        @foreach($job_types as $job_type)
                            <option value="{{$job_type->id}}"
                                    @if($job_type->id === $commessa->job_type->id)
                                        selected
                                    @endif
                            >{{$job_type->description}}</option>
                        @endforeach
                    </select>
                </div>
                @error('job_type_id')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-check me-2"></i>Apertura</span>
                    <input type="date" class="form-control" aria-label="Apertura" name="opening"
                           value="{{ Carbon::parse($commessa->opening)->format('Y-m-d') }}">
                </div>
                @error('opening')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-x me-2"></i>Chiusura</span>
                    <input type="date" class="form-control" aria-label="Chiusura" name="closing"
                           @if($commessa->closing === null)
                               value=""
                           @else
                               value="{{ Carbon::parse($commessa->closing)->format('Y-m-d') }}"
                            @endif
                    >
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
                        @foreach($customers as $customer)
                            @if($customer->id === $commessa->customer->id)
                                <option value="{{$customer->id}}" selected>{{$customer->name}}</option>
                            @else
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endif
                        @endforeach
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
                        @foreach($users as $user)
                            @if($user->id === $commessa->user->id)
                                <option value="{{$user->id}}" selected>{{$user->surname}} {{$user->name}}</option>
                            @else
                                <option value="{{$user->id}}">{{$user->surname}} {{$user->name}}</option>
                            @endif
                        @endforeach
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
