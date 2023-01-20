@extends('layouts.app')
@php($require_navbar_tools = true)


@section('content')

    <div class="container shadow-sm my-3 text-center justify-content-center p-3">
        @unless(count($commesse) === 0)
            <nav>
                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                    <button class="nav-link" id="nav-all-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-all"
                            type="button" role="tab"
                            aria-controls="nav-all"
                            aria-selected="true">Tutte</button>
                    @foreach($statuses as $status)
                        <button class="nav-link" id="nav-{{str_replace(' ','',$status->description)}}-tab"
                                data-bs-toggle="tab" data-bs-target="#nav-{{str_replace(' ','',$status->description)}}"
                                type="button" role="tab"
                                aria-controls="nav-{{str_replace(' ','',$status->description)}}"
                                aria-selected="true">{{$status->description}}</button>
                    @endforeach
                    <div class="ms-auto">
                        <form id="sort" class="d-flex">
                            <select id="sort_key" name="sort_key" class="order_field form-select" style="min-width: 235px">
                                <option value="innerCode"   @if(request('sort_key') === 'innerCode') selected @endif >Numero di commessa interna</option>
                                <option value="outerCode"   @if(request('sort_key') === 'outerCode') selected @endif >Numero di commessa esterna</option>
                                <option value="opening"     @if(request('sort_key') === 'opening') selected @endif >Data di apertura</option>
                                <option value="closing"     @if(request('sort_key') === 'closing') selected @endif >Data di chiusura</option>
                                <option value="created_at"  @if(request('sort_key') === 'created_at') selected @endif >Data di creazione</option>
                                <option value="updated_at"  @if(request('sort_key') === 'updated_at') selected @endif >Ultima modifica</option>
                                <option value="customer_id" @if(request('sort_key') === 'customer_id') selected @endif >Cliente</option>
                                <option value="status_id"   @if(request('sort_key') === 'status_id') selected @endif >Stato della commessa</option>
                                <option value="job_type_id" @if(request('sort_key') === 'job_type_id') selected @endif >Progressi</option>
                            </select>

                            <select id="desc" name="desc" class="order_field form-select ms-2">
                                <option value=true  @if(request('desc') === 'true') selected @endif >Discendente</option>
                                <option value=false @if(request('desc') === 'false') selected @endif >Ascendente</option>
                            </select>

                        </form>
                    </div>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-all"
                     role="tabpanel" aria-labelledby="nav-all-tab"
                     tabindex="0">
                    <div class="row">
                        @foreach($commesse->sortBy(request('sort','status_id'),SORT_REGULAR,request('desc') === 'true' ? true : false) as $commessa)
                            <x-order-card :commessa="$commessa"></x-order-card>
                        @endforeach
                    </div>
                </div>
                @foreach($statuses as $status)
                    <div class="tab-pane fade show" id="nav-{{str_replace(' ','',$status->description)}}"
                         role="tabpanel" aria-labelledby="nav-{{str_replace(' ','',$status->description)}}-tab"
                         tabindex="0">
                        <div class="row">
                            @foreach($commesse as $commessa)
                                @if($status->id === $commessa->status->id)
                                    <x-order-card :commessa="$commessa"></x-order-card>
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
            $(()=>{
                $('.order_field').change(()=>{
                    $('#sort').submit()
                })
            })
        </script>
    </div>
@endsection
