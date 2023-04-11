@extends('layouts.app')
@section('content')
    <div class="container shadow-sm my-3 text-center justify-content-center p-3">
        @unless(count($users) === 0)
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-hired-tab" data-bs-toggle="tab" data-bs-target="#nav-hired" type="button" role="tab" aria-controls="nav-hired" aria-selected="true">Assunti</button>
                    <button class="nav-link" id="nav-resigned-tab" data-bs-toggle="tab" data-bs-target="#nav-resigned" type="button" role="tab" aria-controls="nav-resigned" aria-selected="false">Dimessi</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-hired" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
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
