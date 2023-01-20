<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{__('Order Information')}}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{__("Enter order information")}}</p>
    </header>
    <div class="mt-6 space-y-6" x-data="{job:'1'}">
        <div>
            <x-input-label for="extra" :value="__('Order')"/>
            <select :disabled="type !== '1'" id="order_id" name="extra" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected value="">{{__('Choose an order')}}</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" >({{ $order->innerCode }}) ({{ $order->outerCode }}) - {{ $order->customer->name }} {!! $order->description !== null ? ' &#8594; '.$order->description : '' !!} [{{ $order->status->description }}]</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('extra')" class="mt-2"/>
        </div>
        <div>
            <x-input-label for="extra" :value="__('Job Type')"/>
            <select :disabled="type !== '1'" x-model="job" id="job_type" name="job" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected value="">{{__('Choose a job type')}}</option>
                @foreach($job_types as $job_type)
                    <option x-bind:value="{{ $job_type->id }}" value="{{ $job_type->id }}" >{{ $job_type->description }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('job')" class="mt-2"/>
        </div>
        <ul class="grid gap-6 w-full md:grid-cols-2" x-show="job === '5'">
            <li>
                <input :disabled="type !== '1'" type="radio" id="signed" name="signed" value="1" class="hidden peer" checked>
                <label for="signed" class="inline-flex justify-between items-center p-5 w-full text-gray-500 bg-white rounded-lg border border-gray-200 cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="w-full text-lg font-semibold">{{ __('Signed') }}</div>
                    </div>
                    <svg class="ml-3 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </label>
            </li>
            <li>
                <input :disabled="type !== '1'" type="radio" id="module" name="signed" value="0" class="hidden peer">
                <label for="module" class="inline-flex justify-between items-center p-5 w-full text-gray-500 bg-white rounded-lg border border-gray-200 cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="block">
                        <div class="w-full text-lg font-semibold">{{ __('With Module') }}</div>
                    </div>
                    <svg class="ml-3 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                </label>
            </li>
        </ul>

    </div>
</section>
