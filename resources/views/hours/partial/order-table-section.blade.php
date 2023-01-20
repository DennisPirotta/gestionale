@if($order_hours->count() > 0)
    <tr class="bg-gray-50">
        <td class="py-2 px-4 border-l">{{__('Orders')}}</td>
        <td colspan="{{$period->count()}}" class="py-2 px-4"></td>
    </tr>
    @foreach($order_hours as $order_hour)
        <tr class="bg-gray-100 border-b">
            <th scope="row" class="border-r p-1.5">
                <b>{{ $order_hour->first()->order->innerCode }}</b>
                <p class="mb-0">{{ $order_hour->first()->order->customer->name }}</p>
                <small>{{ $order_hour->first()->order->description }}</small> <br>
                <small>{{ $order_hour->first()->order->outerCode }}</small>
            </th>
            @php($count = 0)
            @foreach($period as $day)
                <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">
                    <div    contenteditable="true"
                            data-order-id="{{ $order_hour->first()->order->id }}"
                            data-date="{{ $day->format('Y-m-d') }}"
                            data-hour-type="1"
                            class="@if($order_hour->contains(function($value,$key) use ($day){ return $value->hour->date == $day->format('Y-m-d'); })) hidden @endif"
                    ></div>
                    @foreach($order_hour as $record)
                        @if($record->hour->date == $day->format('Y-m-d'))
                            @php($count+=$record->hour->count)
                            <div    contenteditable="true"
                                    data-order-id="{{ $record->order->id }}"
                                    data-date="{{ $day->format('Y-m-d') }}"
                                    data-hour-type="1"
                                    data-hour-id="{{ $record->hour->id }}"
                            >
                                {{ $record->hour->count }}
                                <data class="hidden">{{ $record->hour->id }}</data>
                            </div>
                            <div data-popover id="order-{{ $record->id }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0">
                                <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg">
                                    <p class="mb-0 font-semibold text-gray-900">{{__('Edit Job Type')}}</p>
                                </div>
                                <div class="px-3 py-2 relative">
                                    <form class="mb-0 flex justify-center" action="{{ route('order-hours.update',$record) }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <input id="job_type_{{$record->id}}" name="job_type_id" class="hidden">
                                        @foreach(\App\Models\JobType::all() as $job_type)
                                            <button type="submit" onclick="$('#job_type_{{$record->id}}').val({{ $job_type->id }})" class="cursor-pointer mx-1 text-sm font-medium px-2.5 py-0.5 rounded {{ config('colors.job_types.'.$job_type->title) }}">{{ $job_type->title }}</button>
                                        @endforeach
                                    </form>
                                </div>
                                <div data-popper-arrow></div>
                            </div>
                            <div data-popover-target="order-{{ $record->id }}" data-popover-trigger="click" class="cursor-pointer mx-1 text-sm font-medium py-0.5 rounded {{ config('colors.job_types.'.$record->job_type->title) }} ">{{ $record->job_type->title }}</div>
                        @endif
                    @endforeach
                </td>
            @endforeach
            <td>{{ $count }}</td>
        </tr>
    @endforeach
@endif

