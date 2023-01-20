<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{__('Technical Report Information')}}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{__("Enter technical report information")}}</p>
    </header>
    <div class="space-y-6" x-data="{new_fi:'null'}">
        <div>
            <x-input-label for="technical_report_id" :value="__('Technical Report')"/>
            <select :disabled="type !== '2'" x-model="new_fi" id="technical_report_id" name="extra" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="null">{{__('Choose a technical report')}}</option>
                <option value="new">{{__('New technical report')}}</option>
                    @foreach($technical_reports as $technical_report)
                        <option value="{{ $technical_report->id }}" >({{ $technical_report->number }}) - {{ $technical_report->customer->name }} {{ $technical_report->secondary_customer !== null ? ' - '.$technical_report->secondary_customer->name : '' }}</option>
                    @endforeach
            </select>
            <x-input-error :messages="$errors->get('extra')" class="mt-2"/>
        </div>

        <div x-show="new_fi != 'null'">
            <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Night') }}</span>
            <div class="flex items-center mb-4">
                <input :disabled="type !== '2'" id="no-night" type="radio" value="no-night" name="night" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" checked>
                <label for="no-night" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('No Night Spent') }}</label>
            </div>
            <ul class="grid gap-6 w-full md:grid-cols-2">
                <li>
                    <input :disabled="type !== '2'" type="radio" id="eu" name="night" value="eu" class="hidden peer" required>
                    <label for="eu" class="inline-flex justify-between items-center p-5 w-full text-gray-500 bg-white rounded-lg border border-gray-200 cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="block">
                            <div class="w-full text-lg font-semibold">{{__('Night EU')}}</div>
                        </div>
                        <svg class="ml-3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="23px" viewBox="0 0 34 23" version="1.1">
                            <g id="surface1">
                                <rect x="0" y="0" width="34" height="23" style="fill:rgb(0%,20%,60%);fill-opacity:1;stroke:none;"/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17 2.554688 L 16.609375 3.769531 L 17.210938 3.96875 Z M 17 2.554688 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17 2.554688 L 17.390625 3.769531 L 16.789062 3.96875 Z M 17 2.554688 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 18.199219 3.4375 L 16.9375 3.4375 L 16.9375 4.078125 Z M 18.199219 3.4375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 18.199219 3.4375 L 17.179688 4.191406 L 16.808594 3.671875 Z M 18.199219 3.4375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17.738281 4.867188 L 17.351562 3.652344 L 16.753906 3.847656 Z M 17.738281 4.867188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17.738281 4.867188 L 16.722656 4.117188 L 17.089844 3.597656 Z M 17.738281 4.867188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 15.800781 3.4375 L 17.0625 3.4375 L 17.0625 4.078125 Z M 15.800781 3.4375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 15.800781 3.4375 L 16.820312 4.191406 L 17.191406 3.671875 Z M 15.800781 3.4375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 16.261719 4.867188 L 16.648438 3.652344 L 17.246094 3.847656 Z M 16.261719 4.867188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 16.261719 4.867188 L 17.277344 4.117188 L 16.910156 3.597656 Z M 16.261719 4.867188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17 17.890625 L 16.609375 19.105469 L 17.210938 19.300781 Z M 17 17.890625 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17 17.890625 L 17.390625 19.105469 L 16.789062 19.300781 Z M 17 17.890625 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 18.199219 18.773438 L 16.9375 18.773438 L 16.9375 19.410156 Z M 18.199219 18.773438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 18.199219 18.773438 L 17.179688 19.523438 L 16.808594 19.007812 Z M 18.199219 18.773438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17.738281 20.199219 L 17.351562 18.984375 L 16.753906 19.183594 Z M 17.738281 20.199219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 17.738281 20.199219 L 16.722656 19.449219 L 17.089844 18.933594 Z M 17.738281 20.199219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 15.800781 18.773438 L 17.0625 18.773438 L 17.0625 19.410156 Z M 15.800781 18.773438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 15.800781 18.773438 L 16.820312 19.523438 L 17.191406 19.007812 Z M 15.800781 18.773438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 16.261719 20.199219 L 16.648438 18.984375 L 17.246094 19.183594 Z M 16.261719 20.199219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 16.261719 20.199219 L 17.277344 19.449219 L 16.910156 18.933594 Z M 16.261719 20.199219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.445312 10.222656 L 9.054688 11.4375 L 9.652344 11.636719 Z M 9.445312 10.222656 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.445312 10.222656 L 9.832031 11.4375 L 9.234375 11.636719 Z M 9.445312 10.222656 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.640625 11.105469 L 9.382812 11.105469 L 9.382812 11.742188 Z M 10.640625 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.640625 11.105469 L 9.625 11.855469 L 9.253906 11.339844 Z M 10.640625 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.183594 12.535156 L 9.796875 11.320312 L 9.195312 11.515625 Z M 10.183594 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.183594 12.535156 L 9.164062 11.78125 L 9.535156 11.265625 Z M 10.183594 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 8.246094 11.105469 L 9.507812 11.105469 L 9.507812 11.742188 Z M 8.246094 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 8.246094 11.105469 L 9.265625 11.855469 L 9.636719 11.339844 Z M 8.246094 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 8.703125 12.535156 L 9.09375 11.320312 L 9.691406 11.515625 Z M 8.703125 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 8.703125 12.535156 L 9.722656 11.78125 L 9.351562 11.265625 Z M 8.703125 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.480469 5.894531 L 13.5 5.144531 L 13.128906 4.625 Z M 12.480469 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.480469 5.894531 L 12.871094 4.679688 L 13.46875 4.875 Z M 12.480469 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.023438 4.464844 L 13.042969 5.214844 L 13.414062 4.699219 Z M 12.023438 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.023438 4.464844 L 13.285156 4.464844 L 13.285156 5.105469 Z M 12.023438 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.222656 3.582031 L 12.832031 4.796875 L 13.433594 4.996094 Z M 13.222656 3.582031 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.222656 3.582031 L 13.613281 4.796875 L 13.011719 4.996094 Z M 13.222656 3.582031 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.960938 5.894531 L 12.945312 5.144531 L 13.3125 4.625 Z M 13.960938 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.960938 5.894531 L 13.574219 4.679688 L 12.972656 4.875 Z M 13.960938 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 14.417969 4.464844 L 13.402344 5.214844 L 13.03125 4.699219 Z M 14.417969 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 14.417969 4.464844 L 13.160156 4.464844 L 13.160156 5.105469 Z M 14.417969 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.195312 8.699219 L 10.808594 7.484375 L 10.207031 7.683594 Z M 11.195312 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.195312 8.699219 L 10.179688 7.949219 L 10.546875 7.433594 Z M 11.195312 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.714844 8.699219 L 10.734375 7.949219 L 10.363281 7.433594 Z M 9.714844 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.714844 8.699219 L 10.105469 7.484375 L 10.703125 7.683594 Z M 9.714844 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.257812 7.273438 L 10.277344 8.023438 L 10.648438 7.507812 Z M 9.257812 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.257812 7.273438 L 10.519531 7.273438 L 10.519531 7.910156 Z M 9.257812 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.65625 7.273438 L 10.636719 8.023438 L 10.265625 7.507812 Z M 11.65625 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.65625 7.273438 L 10.394531 7.273438 L 10.394531 7.910156 Z M 11.65625 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.457031 6.390625 L 10.847656 7.605469 L 10.246094 7.800781 Z M 10.457031 6.390625 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.457031 6.390625 L 10.066406 7.605469 L 10.667969 7.800781 Z M 10.457031 6.390625 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.65625 14.9375 L 10.394531 14.9375 L 10.394531 15.578125 Z M 11.65625 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.65625 14.9375 L 10.636719 15.691406 L 10.265625 15.171875 Z M 11.65625 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.195312 16.367188 L 10.808594 15.152344 L 10.207031 15.347656 Z M 11.195312 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 11.195312 16.367188 L 10.179688 15.617188 L 10.546875 15.097656 Z M 11.195312 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.714844 16.367188 L 10.734375 15.617188 L 10.363281 15.097656 Z M 9.714844 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.714844 16.367188 L 10.105469 15.152344 L 10.703125 15.347656 Z M 9.714844 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.457031 14.054688 L 10.847656 15.269531 L 10.246094 15.46875 Z M 10.457031 14.054688 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 10.457031 14.054688 L 10.066406 15.269531 L 10.667969 15.46875 Z M 10.457031 14.054688 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.257812 14.9375 L 10.519531 14.9375 L 10.519531 15.578125 Z M 9.257812 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 9.257812 14.9375 L 10.277344 15.691406 L 10.648438 15.171875 Z M 9.257812 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 14.417969 17.746094 L 13.160156 17.746094 L 13.160156 18.382812 Z M 14.417969 17.746094 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 14.417969 17.746094 L 13.402344 18.496094 L 13.03125 17.980469 Z M 14.417969 17.746094 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.960938 19.171875 L 13.574219 17.957031 L 12.972656 18.15625 Z M 13.960938 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.960938 19.171875 L 12.945312 18.421875 L 13.3125 17.90625 Z M 13.960938 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.480469 19.171875 L 13.5 18.421875 L 13.128906 17.90625 Z M 12.480469 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.480469 19.171875 L 12.871094 17.957031 L 13.46875 18.15625 Z M 12.480469 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.222656 16.863281 L 13.613281 18.078125 L 13.011719 18.273438 Z M 13.222656 16.863281 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 13.222656 16.863281 L 12.832031 18.078125 L 13.433594 18.273438 Z M 13.222656 16.863281 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.023438 17.746094 L 13.285156 17.746094 L 13.285156 18.382812 Z M 12.023438 17.746094 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 12.023438 17.746094 L 13.042969 18.496094 L 13.414062 17.980469 Z M 12.023438 17.746094 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.554688 10.222656 L 24.945312 11.4375 L 24.347656 11.636719 Z M 24.554688 10.222656 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.554688 10.222656 L 24.167969 11.4375 L 24.765625 11.636719 Z M 24.554688 10.222656 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.359375 11.105469 L 24.617188 11.105469 L 24.617188 11.742188 Z M 23.359375 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.359375 11.105469 L 24.375 11.855469 L 24.746094 11.339844 Z M 23.359375 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.816406 12.535156 L 24.203125 11.320312 L 24.804688 11.515625 Z M 23.816406 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.816406 12.535156 L 24.835938 11.78125 L 24.464844 11.265625 Z M 23.816406 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 25.753906 11.105469 L 24.492188 11.105469 L 24.492188 11.742188 Z M 25.753906 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 25.753906 11.105469 L 24.734375 11.855469 L 24.363281 11.339844 Z M 25.753906 11.105469 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 25.296875 12.535156 L 24.90625 11.320312 L 24.308594 11.515625 Z M 25.296875 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 25.296875 12.535156 L 24.277344 11.78125 L 24.648438 11.265625 Z M 25.296875 12.535156 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.519531 5.894531 L 20.5 5.144531 L 20.871094 4.625 Z M 21.519531 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.519531 5.894531 L 21.128906 4.679688 L 20.53125 4.875 Z M 21.519531 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.976562 4.464844 L 20.957031 5.214844 L 20.585938 4.699219 Z M 21.976562 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.976562 4.464844 L 20.714844 4.464844 L 20.714844 5.105469 Z M 21.976562 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.777344 3.582031 L 21.167969 4.796875 L 20.566406 4.996094 Z M 20.777344 3.582031 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.777344 3.582031 L 20.386719 4.796875 L 20.988281 4.996094 Z M 20.777344 3.582031 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.039062 5.894531 L 21.054688 5.144531 L 20.6875 4.625 Z M 20.039062 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.039062 5.894531 L 20.425781 4.679688 L 21.027344 4.875 Z M 20.039062 5.894531 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 19.582031 4.464844 L 20.597656 5.214844 L 20.96875 4.699219 Z M 19.582031 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 19.582031 4.464844 L 20.839844 4.464844 L 20.839844 5.105469 Z M 19.582031 4.464844 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.804688 8.699219 L 23.191406 7.484375 L 23.792969 7.683594 Z M 22.804688 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.804688 8.699219 L 23.820312 7.949219 L 23.453125 7.433594 Z M 22.804688 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.285156 8.699219 L 23.265625 7.949219 L 23.636719 7.433594 Z M 24.285156 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.285156 8.699219 L 23.894531 7.484375 L 23.296875 7.683594 Z M 24.285156 8.699219 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.742188 7.273438 L 23.722656 8.023438 L 23.351562 7.507812 Z M 24.742188 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.742188 7.273438 L 23.480469 7.273438 L 23.480469 7.910156 Z M 24.742188 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.34375 7.273438 L 23.363281 8.023438 L 23.734375 7.507812 Z M 22.34375 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.34375 7.273438 L 23.605469 7.273438 L 23.605469 7.910156 Z M 22.34375 7.273438 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.542969 6.390625 L 23.152344 7.605469 L 23.753906 7.800781 Z M 23.542969 6.390625 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.542969 6.390625 L 23.933594 7.605469 L 23.332031 7.800781 Z M 23.542969 6.390625 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.34375 14.9375 L 23.605469 14.9375 L 23.605469 15.578125 Z M 22.34375 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.34375 14.9375 L 23.363281 15.691406 L 23.734375 15.171875 Z M 22.34375 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.804688 16.367188 L 23.191406 15.152344 L 23.792969 15.347656 Z M 22.804688 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 22.804688 16.367188 L 23.820312 15.617188 L 23.453125 15.097656 Z M 22.804688 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.285156 16.367188 L 23.265625 15.617188 L 23.636719 15.097656 Z M 24.285156 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.285156 16.367188 L 23.894531 15.152344 L 23.296875 15.347656 Z M 24.285156 16.367188 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.542969 14.054688 L 23.152344 15.269531 L 23.753906 15.46875 Z M 23.542969 14.054688 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 23.542969 14.054688 L 23.933594 15.269531 L 23.332031 15.46875 Z M 23.542969 14.054688 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.742188 14.9375 L 23.480469 14.9375 L 23.480469 15.578125 Z M 24.742188 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 24.742188 14.9375 L 23.722656 15.691406 L 23.351562 15.171875 Z M 24.742188 14.9375 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 19.582031 17.746094 L 20.839844 17.746094 L 20.839844 18.382812 Z M 19.582031 17.746094 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 19.582031 17.746094 L 20.597656 18.496094 L 20.96875 17.980469 Z M 19.582031 17.746094 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.039062 19.171875 L 20.425781 17.957031 L 21.027344 18.15625 Z M 20.039062 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.039062 19.171875 L 21.054688 18.421875 L 20.6875 17.90625 Z M 20.039062 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.519531 19.171875 L 20.5 18.421875 L 20.871094 17.90625 Z M 21.519531 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.519531 19.171875 L 21.128906 17.957031 L 20.53125 18.15625 Z M 21.519531 19.171875 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.777344 16.863281 L 20.386719 18.078125 L 20.988281 18.273438 Z M 20.777344 16.863281 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 20.777344 16.863281 L 21.167969 18.078125 L 20.566406 18.273438 Z M 20.777344 16.863281 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.976562 17.746094 L 20.714844 17.746094 L 20.714844 18.382812 Z M 21.976562 17.746094 "/>
                                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,80%,0%);fill-opacity:1;" d="M 21.976562 17.746094 L 20.957031 18.496094 L 20.585938 17.980469 Z M 21.976562 17.746094 "/>
                            </g>
                        </svg>
                    </label>
                </li>
                <li>
                    <input :disabled="type !== '2'" type="radio" id="xeu" name="night" value="xeu" class="hidden peer">
                    <label for="xeu" class="inline-flex justify-between items-center p-5 w-full text-gray-500 bg-white rounded-lg border border-gray-200 cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <div class="block">
                            <div class="w-full text-lg font-semibold">{{__('Night Extra EU')}}</div>
                        </div>
                        <svg class="ml-3 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </label>
                </li>
            </ul>
            <x-input-error class="mt-2" :messages="$errors->get('night')"/>
        </div>

        <div x-show="new_fi == 'new'">
            @include('hours.partial.create-technical-report-form')
        </div>
    </div>
</section>
