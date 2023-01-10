@if($technical_report_hours->count() > 0)
    <tr class="bg-gray-50">
        <td class="py-2 px-4 border-l">{{__('Technical Reports')}}</td>
        <td colspan="{{$period->count()}}" class="py-2 px-4"></td>
    </tr>
    @foreach($technical_report_hours as $technical_report_hour)
        <tr class="bg-gray-100 border-b">
            <th scope="row" class="border-r p-1.5">
                {{ $technical_report_hour->first()->technical_report->number }} <br>
                {{ $technical_report_hour->first()->technical_report->customer->name }}
            </th>
            @php($count=0)
            @foreach($period as $day)
                <td class="border-r">
                    <div    contenteditable="true"
                            data-technical-report-id="{{ $technical_report_hour->first()->technical_report->id }}"
                            data-date="{{ $day->format('Y-m-d') }}"
                            data-hour-type="2"
                    >
                        @foreach($technical_report_hour as $record)
                            @if($record->hour->date == $day->format('Y-m-d'))
                                @php($count+=$record->hour->count)
                                    {{ $record->hour->count }}
                                    <data class="hidden">{{ $record->hour->id }}</data>
                                @if($record->nightEU)
                                </div>
                                <div data-popover-target="night-{{ $record->id }}" data-popover-trigger="click" class="cursor-pointer mx-1 bg-blue-200 text-blue-800 text-sm font-medium px-1 py-0.5 rounded">
                                    EU
                                @elseif($record->nightExtraEU)
                                </div>
                                <div data-popover-target="night-{{ $record->id }}" data-popover-trigger="click" class="cursor-pointer mx-1 bg-green-300 text-green-800 text-sm font-medium px-1 py-0.5 rounded">
                                    XEU
                                @else
                                </div>
                                <div data-popover-target="night-{{ $record->id }}" data-popover-trigger="click" class="cursor-pointer mx-1 bg-yellow-100 text-yellow-800 text-sm font-medium px-1 py-0.5 rounded">
                                    NO
                                @endif
                                    <div data-popover id="night-{{ $record->id }}" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0">
                                        <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg">
                                            <p class="mb-0 font-semibold text-gray-900">{{__('Edit Night')}}</p>
                                        </div>
                                        <div class="px-3 py-2">
                                            <form class="mb-0 flex justify-center" action="{{ route('technical-report-hours.update',$record) }}" method="post">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="technical_report_hour_id" value="{{ $record->id }}">
                                                <input type="hidden" id="night_eu_{{$record->id}}" name="night_eu">
                                                <input type="hidden" id="night_xeu_{{$record->id}}" name="night_xeu">
                                                <button type="submit" onclick="$('#night_eu_{{$record->id}}').val(1);$('#night_xeu_{{$record->id}}').val(0);" class="cursor-pointer mx-2 text-sm font-medium px-2.5 py-0.5 rounded bg-blue-200 text-blue-800">
                                                    EU
                                                </button>
                                                <button type="submit" onclick="$('#night_eu_{{$record->id}}').val(0);$('#night_xeu_{{$record->id}}').val(1);" class="cursor-pointer mx-2 text-sm font-medium px-2.5 py-0.5 rounded bg-green-300 text-green-800">
                                                    XEU
                                                </button>
                                                <button type="submit" onclick="$('#night_eu_{{$record->id}}').val(0);$('#night_xeu_{{$record->id}}').val(0);" class="cursor-pointer mx-2 text-sm font-medium px-2.5 py-0.5 rounded bg-yellow-100 text-yellow-800">
                                                    NO
                                                </button>
                                            </form>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                            @endif
                        @endforeach
                    </div>
                </td>
            @endforeach
            <td>{{ $count }}</td>
        </tr>
    @endforeach
@endif
