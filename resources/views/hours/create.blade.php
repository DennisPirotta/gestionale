@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css" />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <div style="padding-top:70px;">
        <form action="{{ route('hours.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="{type: '{{ Session::get('hour_type','') }}'}">

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('hours.partial.create-hour-basic-fields')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" x-show="type === '1'">
                    <div class="max-w-xl">
                        @include('hours.partial.create-hour-orders-fields')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg" x-show="type === '2'">
                    <div class="max-w-xl">
                        @include('hours.partial.create-hour-technical-report-fields')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <x-primary-button>
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            {{ __('Save') }}
                        </x-primary-button>
                    </div>
                </div>

            </div>

        </form>

        <script>
            $(()=>{
                let user = sessionStorage.getItem('user')
                $('#user_id').children().each( (i,e) => {
                    if (e.value == user){
                        $(e).attr('selected',true)
                    }
                } )
            })
        </script>
    </div>
@endsection
