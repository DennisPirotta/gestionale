@extends('layouts.app')
@php($require_navbar_tools = true)

@section('content')
    <div class="container shadow-sm my-3 text-center justify-content-center p-3">
        @unless(count($commesse) === 0)
            <nav>
                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                    @foreach($statuses as $status)
                        <button class="nav-link" id="nav-{{str_replace(' ','',$status->description)}}-tab"
                                data-bs-toggle="tab" data-bs-target="#nav-{{str_replace(' ','',$status->description)}}"
                                type="button" role="tab"
                                aria-controls="nav-{{str_replace(' ','',$status->description)}}"
                                aria-selected="true">{{$status->description}}</button>
                    @endforeach
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                @foreach($statuses as $status)
                    <div class="tab-pane fade show" id="nav-{{str_replace(' ','',$status->description)}}"
                         role="tabpanel" aria-labelledby="nav-{{str_replace(' ','',$status->description)}}-tab"
                         tabindex="0">
                        <div class="row">
                            @foreach($commesse as $commessa)
                                @if($status->id === $commessa->status)
                                    <div class="col-sm-6 col-md-4 mb-3">
                                        <div
                                            class="card h-100 bg-opacity-25 bg-{{$statuses->where('id',$commessa['status'])->value('color')}}">
                                            <!--  bg-black bg-opacity-25 -->
                                            <div class="card-body">
                                                <i class="bi bi-building"></i>
                                                <span class="card-title">Commessa {{$commessa['id']}}</span>
                                                <a href="?company={{urlencode($companies->where('id',$commessa['company'])->value('name'))}}">
                                                    @if($commessa->company === 1)
                                                        <span class="badge text-bg-primary bg-opacity-100">3D</span>
                                                    @else
                                                        <span class="badge text-bg-success bg-opacity-100">S+H</span>
                                                    @endif
                                                </a>
                                                <a href="?customer={{$customers->where('id',$commessa['customer'])->value('name')}}">
                                                    <h6 class="card-subtitle my-2 text-muted">{{$customers->where('id',$commessa['customer'])->value('name')}}</h6>
                                                </a>
                                                <p class="card-text">{{$commessa['description']}}</p>
                                                <div class="d-flex justify-content-center">
                                                    <a href="/commesse/{{$commessa['id']}}">
                                                        <button class="btn btn-outline-primary me-2">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Dettagli
                                                        </button>
                                                    </a>

                                                    <form method="POST" action="/commesse/{{$commessa['id']}}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-outline-danger"
                                                                onclick="return confirm('Sicuro di voler Eliminare?')">
                                                            <i class="bi bi-trash me-1"></i>
                                                            Elimina
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col">
                <img src="{{asset('images/no-orders.svg')}}" loading="lazy" class="w-50" alt="">
                <p class="fs-3 font-monospace">Nessuna commessa trovata</p>
            </div>
        @endunless
        <script>
            let nav = document.querySelector("#nav-tab button:first-child")
            content = document.querySelector("#nav-tabContent div:first-child")
            nav.classList.add("active")
            content.classList.add("active")
        </script>
    </div>
@endsection
