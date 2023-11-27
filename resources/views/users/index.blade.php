{{--@dd($invoices)--}}
@extends('layouts.app')
@section('content')
    <div class="container shadow-sm my-3 text-center justify-content-center p-3">
        @unless(count($users) === 0)
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link @if(!request()->has(['from','to'])) active @endif" id="nav-hired-tab" data-bs-toggle="tab" data-bs-target="#nav-hired" type="button" role="tab" aria-controls="nav-hired" aria-selected="{{ !request()->has(['from','to']) }}">Assunti</button>
                    <button class="nav-link" id="nav-resigned-tab" data-bs-toggle="tab" data-bs-target="#nav-resigned" type="button" role="tab" aria-controls="nav-resigned" aria-selected="false">Dimessi</button>
                    <button class="nav-link  @if(request()->has(['from','to'])) active @endif" id="nav-invoices-tab" data-bs-toggle="tab" data-bs-target="#nav-invoices" type="button" role="tab" aria-controls="nav-invoices" aria-selected="{{ request()->has(['from','to']) }}">Fatturato</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade @if(!request()->has(['from','to'])) show active @endif" id="nav-hired" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="row pt-3 ">
                        @foreach($users->where('hired',true) as $user)
                            <x-user-card :user="$user" :hired="true"></x-user-card>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-resigned" role="tabpanel" aria-labelledby="nav-resigned-tab" tabindex="0">
                    <div class="row pt-3">
                        @forelse($users->where('hired',false) as $user)
                            <x-user-card :user="$user" :hired="false"></x-user-card>
                        @empty
                            <h1>Nessun Dipendente Dimesso</h1>
                        @endforelse
                    </div>
                </div>
                <div class="tab-pane fade  @if(request()->has(['from','to'])) show active @endif" id="nav-invoices" role="tabpanel" aria-labelledby="nav-invoices-tab" tabindex="0">

                    <form class="py-2 d-flex align-items-center">
                        <label class="me-2" for="from">Da</label>
                        <input type="date"
                               lang="it_IT"
                               class="form-control"
                               name="from"
                               id="from"
                               value="{{ request('from') !== null ? Carbon\Carbon::parse(request('from'))->format('Y-m-d') : ''}}">
                        <label class="mx-2" for="to">a</label>
                        <input type="date"
                               lang="it_IT"
                               class="form-control"
                               name="to"
                               id="to"
                               value="{{ request('from') !== null ? Carbon\Carbon::parse(request('from'))->format('Y-m-d') : ''}}">
                        <button class="btn btn-primary ms-2" type="submit" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </button>
                    </form>
                        <x-invoices-table :invoices="$invoices"/>
                        <form action="{{ route('invoices.export') }}">
                            <input hidden
                                   value="{{ request('from') !== null ? Carbon\Carbon::parse(request('from'))->format('Y-m-d') : ''}}">
                            <input hidden
                                   value="{{ request('to') !== null ? Carbon\Carbon::parse(request('to'))->format('Y-m-d') : ''}}">
                            <button class="btn btn-primary ms-2">
                                Export
                            </button>
                        </form>
                </div>
            </div>
        @else
            <div class="col">
                <img src="{{asset('images/no-orders.svg')}}" loading="lazy" class="w-50" alt="">
                <p class="fs-3 font-monospace">Nessun cliente trovato</p>
            </div>
        @endunless
            <div class="modal fade" id="toolModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" id="modifyForm">
                            @csrf
                            @method('PUT')
                            <div class="modal-body d-flex">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Nome Cliente</span>
                                    <input type="text" class="form-control" aria-label="Nome Cliente" name="name"
                                           value="" placeholder="Test">
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
    </div>
@endsection
