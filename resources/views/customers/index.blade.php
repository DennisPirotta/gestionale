@extends('layouts.app')
@section('content')
    <div class="container shadow-sm my-3 justify-content-center p-3">
        <div class="d-md-flex align-content-center align-middle">
            <h2 class="my-auto">Clienti</h2>
            <button class="btn btn-primary ms-auto" onclick="add()">Aggiungi Cliente</button>
        </div>
        <hr>
        @unless(count($customers) === 0)
            <div class="row text-center mt-4">
                @foreach($customers as $customer)
                    <x-customer-card :customer="$customer"></x-customer-card>
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
                            <label class="d-none">
                                <input type="text" value="PUT" name="_method">
                            </label>
                            <div class="modal-body d-flex">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Nome Cliente</span>
                                    <input type="text" class="form-control" aria-label="Nome Cliente" name="name"
                                           value="{{ old('name') }}" placeholder="Test" id="name">
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
                $('#toolModal').modal('toggle')
                $('input[name="name"]').val(cliente.name)
                $('input[name="_method"]').val("PUT")
                $('#modifyForm').attr('action',`/clienti/${cliente.id}`)
            }
            function add(){
                $('#toolModal').modal('toggle')
                $('input[name="_method"]').val("")
                $('#modifyForm').attr('action',`/clienti`)
            }
        </script>
    </div>
@endsection
