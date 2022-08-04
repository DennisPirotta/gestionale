@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
    <div class="container my-5 p-4 shadow-sm">
        <form method="POST" action="/commesse/{{$commessa->id}}" class="row" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-md-4 col-sm-6">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-building me-2"></i>Compagnia</label>
                    <select class="form-select" id="inputGroupSelect01" name="company">
                        @foreach($companies as $company)
                            @if($company->id == $commessa->company)
                                <option value="{{$company->id}}" selected>{{$company->name}}</option>
                            @else
                                <option value="{{$company->id}}">{{$company->name}}</option>
                            @endif
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
                    <select class="form-select" id="inputGroupSelect01" name="status">
                        @foreach($statuses as $status)
                            @if($status->id == $commessa->status)
                                <option value="{{$status->id}}" selected>{{$status->description}}</option>
                            @else
                                <option value="{{$status->id}}">{{$status->description}}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('status')
                    <p class="text-danger fs-6">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-globe2 me-2"></i>Paese</label>
                    <select class="form-select" id="inputGroupSelect01" name="country">
                        @foreach($countries as $country)
                            @if($country->id == $commessa->country)
                                <option value="{{$country->id}}" selected>{{$country->name}}</option>
                            @else
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endif
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
                    <input type="text" class="form-control" aria-label="Descrizione" name="description" value="{{$commessa->description}}">
                </div>
                @error('description')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SW</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSW" value="{{$commessa->hourSW}}">
                </div>
                @error('hourSW')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore MS</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourMS" value="{{$commessa->hourMS}}">
                </div>
                @error('hourMS')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore FAT</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourFAT" value="{{$commessa->hourFAT}}">
                </div>
                @error('hourFAT')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clock me-2"></i>Ore SAF</span>
                    <input type="number" class="form-control" aria-label="Descrizione" name="hourSAF" value="{{$commessa->hourSAF}}">
                </div>
                @error('hourSAF')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-arrow-clockwise me-2"></i>Progressi</span>
                    <input type="text" class="form-control" aria-label="Progressi" name="progress" value="{{$commessa->progress}}">
                </div>
                @error('progress')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-check me-2"></i>Apertura</span>
                    <input type="date" class="form-control" aria-label="Apertura" name="opening" value="{{$commessa->opening}}">
                </div>
                @error('opening')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-calendar-x me-2"></i>Chiusura</span>
                    <input type="date" class="form-control" aria-label="Chiusura" name="closing" value="{{$commessa->closing}}">
                </div>
                @error('closing')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <div class=" col-md-4 col-sm-6">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <label class="input-group-text" for="inputGroupSelect01"><i class="bi bi-person me-2"></i>Cliente</label>
                    <select class="form-select" id="inputGroupSelect01" name="customer">
                            @foreach($customers as $customer)
                                @if($customer->id == $commessa->customer)
                                    <option value="{{$customer->id}}" selected>{{$customer->name}}</option>
                                @else
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endif
                            @endforeach
                    </select>
                </div>
                @error('customer')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">Salva</button>
        </form>
    </div>
@endsection
