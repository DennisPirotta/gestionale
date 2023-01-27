@foreach($other_hours as $type=>$other_hour)
    <tr class="bg-gray-50">
        <td class="py-2 px-4 border-l">{{ __(\App\Models\HourType::find($type)->description) }}</td>
        <td colspan="{{$period->count()}}" class="py-2 px-4"></td>
    </tr>
    <tr class="bg-gray-100 border-b">
        <th scope="row" class="border-r p-1.5"></th>
        @php($count = 0)
        @foreach($period as $day)
            <td class="border-r @if($day->isWeekend() || $day->isHoliday()) bg-opacity-25 bg-primary @endif ">
                <div    contenteditable="true"
                        data-date="{{ $day->format('Y-m-d') }}"
                        data-hour-type="{{ $type }}"
                        class="@if($other_hour->contains(function($value,$key) use ($day){ return $value->date == $day->format('Y-m-d'); })) hidden @endif"
                ></div>
                @foreach($other_hour as $record)
                    @if($record->date == $day->format('Y-m-d'))
                        @php($count+=$record->count)
                        <div    contenteditable="true"
                                data-date="{{ $day->format('Y-m-d') }}"
                                data-hour-type="{{ $type }}"
                        >
                            {{ $record->count }}
                            <data class="hidden">{{ $record->id }}</data>
                        </div>
                        @if($record->description)
                            <i class="bi bi-info-circle text-blue-500" data-bs-toggle="popover" data-bs-title="Info" data-bs-content="{{ $record->description }}"></i>
                        @endif
                    @endif
                @endforeach
            </td>
        @endforeach
        <td>{{ $count }}</td>
    </tr>
@endforeach
