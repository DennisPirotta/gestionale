@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
    <div class="container shadow-sm my-3 text-center justify-content-center p-3">
        @unless(count($customers) == 0)
            <div class="row">
                @foreach($customers as $customer)
                    <div class="col-sm-6 col-md-4 mb-3">
                        <div class="card h-100"> <!--  bg-black bg-opacity-25 -->
                            <div class="card-body">
                                <i class="bi bi-person"></i>
                                <span class="card-title">Cliente {{$customer['id']}}</span>
                                <p class="card-text">{{$customer['name']}}</p>
                                <div class="d-flex justify-content-center">
                                    <a href="/clienti/{{$customer['id']}}/edit">
                                        <button class="btn btn-outline-primary me-2">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Modifica
                                        </button>
                                    </a>

                                    <form method="POST" action="/clienti/{{$customer['id']}}">
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
                @endforeach
            </div>
        @else
            <div class="col">
                <img src="{{asset('images/no-orders.svg')}}" loading="lazy" class="w-50" alt="">
                <p class="fs-3 font-monospace">Nessun cliente trovato</p>
            </div>
        @endunless
    </div>
@endsection
