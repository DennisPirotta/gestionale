@extends('layouts.app')
@section('content')
    @role('developer')
    @else
        <div class="container shadow-sm mt-5 p-5">
            <div class="d-flex align-items-center">
                <span class="m-0 h1"><i class="bi bi-bug me-2 text-danger"></i>Bug Report</span>
            </div>
            <hr class="mb-3">
            <form action="{{ route('bug.report.send') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="object" class="form-label">Pagina dove si verifica l'errore</label>
                    <input type="text" class="form-control @error('object') is-invalid @enderror " name="object"
                           id="object">
                    @error('object')
                    <p class="text-danger">{{ __($message) }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descrizione</label>
                    <textarea class="form-control @error('description') is-invalid @enderror " id="description"
                              name="description" rows="3"></textarea>
                    @error('description')
                    <p class="text-danger">{{ __($message) }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Invia Segnalazione</button>
            </form>
        </div>
    @endrole

    @unless($bugs->count() <= 0)
        <div class="container shadow-sm mt-5 p-5 table-responsive">
            <div class="d-flex align-items-center">
                <span class="m-0 h1">Bug noti</span>
            </div>
            <hr class="mb-3">
            <table class="table">
                <thead>
                <tr>
                    <th>Pagina</th>
                    <th>Descrizione</th>
                    <th>Stato</th>
                    @role('developer')
                    <th>Dettagli</th>
                    @endrole
                </tr>
                </thead>
                <tbody>
                @foreach($bugs as $bug)
                    <tr class="align-middle">
                        <td>{{ $bug->object }}</td>
                        <td>{{ $bug->description }}</td>
                        @if($bug->fixed)
                            <td><i class="bi bi-check2-all me-2 text-success"></i>Risolto</td>
                            @role('developer')
                            <td>
                                report by <b>{{ $bug->reporter->name }} {{ $bug->reporter->surname }}</b>
                            </td>
                            @endrole
                        @else
                            <td><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Non Risolto</td>
                            @role('developer')
                            <td>
                                <form action="{{ route('bug.report.update',$bug->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-success"><i class="bi bi-check2-all fs-6"></i></button>
                                </form>
                            </td>
                            @endrole
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="container shadow-sm mt-5 p-5">
            <div class="d-flex align-items-center">
                <span class="m-0 h1"><i class="bi bi-bug me-2 text-success"></i>Nessun Bug riportato</span>
            </div>
        </div>
    @endunless
@endsection
