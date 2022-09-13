@extends('layouts.app')
@php($require_navbar_tools = true)
@section('content')
    <div class="container my-5 p-4 shadow-sm w-50">
        <form method="post" action="/clienti" class="row">
            @csrf
            <div class="col">
                <div class="input-group mb-3 col-md-4 col-sm-6">
                    <span class="input-group-text"><i class="bi bi-clipboard-data me-2"></i>Nome Cliente</span>
                    <input type="text" class="form-control" aria-label="Nome Cliente" name="name" id="name" value="{{ old('name') }}">
                </div>
                @error('name')
                <p class="text-danger fs-6">{{$message}}</p>
                @enderror
                <button class="btn btn-primary w-100" type="submit">Salva</button>
            </div>
        </form>
    </div>
@endsection
