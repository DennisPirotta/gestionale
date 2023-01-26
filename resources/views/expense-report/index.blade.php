@php use Carbon\Carbon @endphp
@extends('layouts.app')
<style>
    @media print {
        @page {
            size: landscape;
            margin: 0
        }
        select, input, .btn{
            display: none !important;
        }
    }
</style>
@section('content')
    <div class="container table-responsive mt-4 p-5" id="main_container">
        <div class="d-flex align-items-center">
            <span class="m-0 h1">Rimborso {{ $month->translatedFormat('F Y') }} {{ $user->name }} {{ $user->surname }}</span>
            <form class="ms-auto d-flex me-2 align-items-center my-0" id="user_form">
                @role('admin|boss')
                <label for="user_select"></label><select id="user_select" class="form-select me-2" name="user">
                    @foreach($users as $current)
                        <option value="{{ $current->id }}"
                                @if(request('user') === (string) $current->id) selected @endif>{{ $current->surname }} {{ $current->name }}</option>
                    @endforeach
                </select>
                @endrole
                <label>
                    <input type="month" name="month" id="mese" class="form-control" value="{{ $month->format('Y-m') }}">
                </label>
            </form>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#add">Aggiungi nota</button>
            <button class="btn btn-success align-self-stretch" id="stampa"><i class="bi bi-printer"></i></button>
        </div>
        <hr>
        <table class="table text-center mt-3">
            <thead>
            <tr>
                <th scope="col">Giorno</th>
                <th scope="col">Cliente</th>
                <th scope="col">Località</th>
                <th scope="col">Km</th>
                <th scope="col">Vitto e alloggio</th>
                <th scope="col">Varie</th>
                <th scope="col">Trasporti</th>
                <th scope="col">Totale</th>
                <th scope="col">Note</th>
                <th scope="col">Azioni</th>
            </tr>
            </thead>
            <tbody>
            @php($tot = [ 'km' => 0, 'food' => 0, 'various' => 0, 'transport' => 0 ])
            @foreach($reports as $report)
                <tr>
                    <?php
                        $tot['km'] += $report->km ?? 0;
                        $tot['food'] += $report->food ?? 0;
                        $tot['various'] += $report->various ?? 0;
                        $tot['transport'] += $report->transport ?? 0;
                    ?>
                    <td>{{ Carbon::parse($report->date)->translatedFormat('D j M Y') }}</td>
                    @if($report->customer !== null)
                        <td>{{ $report->customer->name }}</td>
                    @else
                        <td>//</td>
                    @endif

                    <td>{{ $report->location }}</td>
                    <td>{{ $report->km }}Km - {{ $report->km*0.4 }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $report->food }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $report->various }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $report->transport }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $report->food + $report->various + $report->transport + $report->km*0.4 }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $report->note ?? '/' }}</td>
                    <td>
                        <form class="m-0" action="{{ route('expense_report.destroy',$report) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Eliminare la nota spese?')" type="submit">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
                <tr>
                    <th scope="row" colspan="3">Totale</th>
                    <td>{{ $tot['km'] }}Km - {{ $tot['km']*0.4 }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $tot['food'] }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $tot['various'] }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $tot['transport'] }} <i class="bi bi-currency-euro"></i></td>
                    <td>{{ $tot['km'] + $tot['food'] + $tot['various'] + $tot['transport'] }} <i class="bi bi-currency-euro"></i></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="add_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="add_label">Aggiungi nota</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('expense_report.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-calendar-date fs-5"></i></span>
                            <div class="form-floating">
                                <input type="date" name="date" class="form-control" id="date" placeholder="Giorno"
                                       value="{{ Carbon::now()->format('Y-m-d') }}" required>
                                <label for="date">Giorno</label>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person fs-5"></i></span>
                            <div class="form-floating">
                                <select class="form-select" id="customer" name="customer_id">
                                    <option selected value="">Nessun cliente</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                <label for="customer">Cliente</label>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-geo-alt fs-5"></i></span>
                            <div class="form-floating">
                                <input type="text" name="location" class="form-control" id="location"
                                       placeholder="Località" required>
                                <label for="location">Località</label>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-signpost-2 fs-5"></i></span>
                            <div class="form-floating">
                                <input type="number" step="0.1" name="km" class="form-control" id="km" placeholder="Km" >
                                <label for="km">Km</label>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-house fs-5"></i></span>
                            <div class="form-floating">
                                <input type="number" step="0.05" name="food" class="form-control" id="food"
                                       placeholder="Vitto e alloggio" >
                                <label for="food">Vitto e alloggio</label>
                            </div>
                            <span class="input-group-text"><i class="bi bi-currency-euro fs-5"></i></span>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-credit-card-2-front fs-5"></i></span>
                            <div class="form-floating">
                                <input type="number" step="0.05" name="various" class="form-control" id="various"
                                       placeholder="Spese varie" >
                                <label for="various">Spese varie</label>
                            </div>
                            <span class="input-group-text"><i class="bi bi-currency-euro fs-5"></i></span>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-bicycle fs-5"></i></span>
                            <div class="form-floating">
                                <input type="number" step="0.05" name="transport" class="form-control" id="transport"
                                       placeholder="Trasporti" >
                                <label for="transport">Trasporti</label>
                            </div>
                            <span class="input-group-text"><i class="bi bi-currency-euro fs-5"></i></span>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-clipboard fs-5"></i></span>
                            <div class="form-floating">
                                <input type="text" name="note" class="form-control" id="note" placeholder="Note">
                                <label for="note">Note</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="{{ request('user',auth()->id()) }}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(() => {
            $('#user_select').on('change', (e) => {
                $('#user_form').submit()
            })
            $('#mese').on('change', (e) => {
                $('#user_form').submit()
            })

            $('#stampa').on('click',()=>{
                let main = $('#main_container')
                main.addClass('container-fluid')
                main.removeClass('container')
                window.print()
                main.addClass('container')
                main.removeClass('container-fluid')
            })
        })
    </script>
@endsection
