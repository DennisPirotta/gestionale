<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{__('Technical Report Information')}}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{__("Enter technical report information")}}</p>
    </header>
    <div class="mt-6 space-y-6" x-data="{new_fi:false}">
        <div>
            <x-input-label for="technical_report_id" :value="__('Technical Report')"/>
            <select :disabled="type !== '2'" x-model="new_fi" id="technical_report_id" name="extra" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected value="">{{__('Choose a technical report')}}</option>
                <option value="new">{{__('New technical report')}}</option>
                @foreach($technical_reports as $technical_report)
                    <option :value="false" value="{{ $technical_report->id }}" >({{ $technical_report->number }}) - {{ $technical_report->customer->name }} {{ $technical_report->secondary_customer !== null ? ' - '.$technical_report->secondary_customer->name : '' }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('extra')" class="mt-2"/>
        </div>
        <div x-show="new_fi">
            @include('hours.partial.create-technical-report-form')
        </div>
    </div>
</section>