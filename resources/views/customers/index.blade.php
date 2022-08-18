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
                                    <a >
                                        <button class="btn btn-outline-primary me-2" onclick="modify({{$customer}})">
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
        <script>
            function modify(cliente){
                $('#toolModal').modal('toggle');
                $('input[name="name"]').val(cliente.name)
                $('#modifyForm').attr('action',`/clienti/${cliente.id}`)
            }
        </script>
    </div>
@endsection
