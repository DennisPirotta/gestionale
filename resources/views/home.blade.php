@extends('layouts.app')
@section('content')
    @include('components.flash-messages')
    <div class="container my-5 p-3 justify-content-center text-center">
        <div class="row">
            <div class="col">
                <a href="commesse">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-building fs-1"></i>
                            <h5 class="card-title">Commesse</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="clienti">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-people fs-1"></i>
                            <h5 class="card-title">Clienti</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="ferie">
                    <div class="card">
                        <div class="card-body">
                            <i class="bi bi-calendar4-week fs-1"></i>
                            <h5 class="card-title">Ferie</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
