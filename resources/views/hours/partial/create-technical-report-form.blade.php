<div class="space-y-6">
    <div>
        <x-input-label for="number" :value="__('Number')"/>
        <x-text-input x-bind:disabled="type !== '2'" id="number" name="number" type="text" class="mt-1 block w-full"/>
        <x-input-error class="mt-2" :messages="$errors->get('number')"/>
    </div>
    <div>
        <x-input-label for="fi_order_id" :value="__('Order')"/>
        <select :disabled="type !== '2'" id="fi_order_id" name="fi_order_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected value="">{{__('Choose an order')}}</option>
            <option value="">{{__('Without order')}}</option>
            @foreach($orders as $order)
                <option value="{{ $order->id }}" >({{ $order->innerCode }}) ({{ $order->outerCode }}) - {{ $order->customer->name }} {!! $order->description !== null ? ' &#8594; '.$order->description : '' !!} [{{ $order->status->description }}]</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('fi_order_id')"/>
    </div>
    <div>
        <x-input-label for="customer_id" :value="__('Customer')"/>
        <select :disabled="type !== '2'" id="customer_id" name="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected>{{__('Choose a customer')}}</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" >{{ $customer->name }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('customer_id')"/>
    </div>
    <div>
        <x-input-label for="secondary_customer_id" :value="__('Secondary Customer')"/>
        <select :disabled="type !== '2'" id="secondary_customer_id" name="secondary_customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="" selected>{{__('Without customer')}}</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" >{{ $customer->name }}</option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('secondary_customer_id')"/>
    </div>
</div>
