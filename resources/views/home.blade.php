@extends('layouts.app')
@section('content')
    @include('components.flash-messages')
    <div class="container my-5 p-3 justify-content-center text-center">
        <div class="row g-3">
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/commesse')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-building fs-1"></i>
                            <h5 class="card-title">Commesse</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/clienti')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-people fs-1"></i>
                            <h5 class="card-title">Clienti</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/ferie')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-calendar4-week fs-1"></i>
                            <h5 class="card-title">Ferie</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/ore')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-person-plus fs-1"></i>
                            <h5 class="card-title">Gestione ore</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <a href="{{url('/commesse/report')}}">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-journals fs-1"></i>
                            <h5 class="card-title">Gestione ore</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
