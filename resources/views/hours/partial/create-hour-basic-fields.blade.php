@php use Carbon\Carbon; @endphp
<style>
    .datepicker{
        top: 150px !important;
    }
</style>
<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{__('Basic Information')}}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{__("Enter basic information")}}</p>
    </header>
    <div class="mt-6 space-y-6" x-data="{multiple:false}">

        @role('admin|boss')
        <div>
            <x-input-label for="user_id" :value="__('User')"/>
            <select id="user_id" name="user_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @foreach(\App\Models\User::orderBy('surname')->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->surname }} {{ $user->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('user_id')"/>
        </div>
        @endrole

        <div>
            <x-input-label for="count" :value="__('Count')"/>
            <x-text-input id="count" name="count" type="number" step="0.5" class="mt-1 block w-full" :value="8" required/>
            <x-input-error class="mt-2" :messages="$errors->get('count')"/>
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')"/>
            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full"/>
            <x-input-error class="mt-2" :messages="$errors->get('description')"/>
        </div>


        <label class="inline-flex relative items-center cursor-pointer">
            <input type="checkbox" value="" class="sr-only peer" @click="multiple = !multiple">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Multiple Entry') }}</span>
        </label>

        <div class="-mt-1 relative" x-show="!multiple">
            <x-input-label for="date" :value="__('Date')"/>
            <div class="relative mt-1">
                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                              clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input x-bind:disabled="multiple" id="date" name="date"
                       type="text"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="{{__('Select date')}}" autocomplete="off">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('date')"/>
        </div>

        <div class="-mt-1" x-show="multiple">
            <x-input-label for="date-multiple" :value="__('Date')"/>
            <div
                 class="flex items-center mt-1" id="date-multiple">
                <span class="mx-4 text-gray-500">{{__('From')}}</span>
                <div class="relative">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input x-bind:disabled="!multiple" name="start" type="text"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="{{__('Select date start')}}" autocomplete="off">
                    <x-input-error class="mt-2" :messages="$errors->get('start')"/>
                </div>
                <span class="mx-4 text-gray-500">{{__('To')}}</span>
                <div class="relative">
                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                             viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input x-bind:disabled="!multiple" name="end" type="text"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           placeholder="{{__('Select date end')}}" autocomplete="off">
                    <x-input-error class="mt-2" :messages="$errors->get('end')"/>
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="technical_report_id" :value="__('Hour Type')"/>
            <select x-model="type" id="hour_type_id" name="hour_type_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected x-bind:value="0">{{__('Choose an hour type')}}</option>
                @foreach($hour_types as $hour_type)
                    <option x-bind:value="{{ $hour_type->id }}"
                            value="{{ $hour_type->id }}">{{ $hour_type->description }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('hour_type_id')"/>
        </div>
    </div>

    <script>
        $(()=>{
            const dateEl = document.getElementById('date');
            new Datepicker(dateEl, {
                autohide: true,
                clearBtn: true,
                format: 'yyyy-mm-dd',
                todayBtn: true,
                todayBtnMode: 1,
                language: 'it'
            });
            const dateRangeStartEl = document.getElementById('date-multiple');
            new DateRangePicker(dateRangeStartEl, {
                autohide: true,
                clearBtn: true,
                format: 'yyyy-mm-dd',
                todayBtn: true,
                todayBtnMode: 1,
                language: 'it'
            });
        })
    </script>
</section>
