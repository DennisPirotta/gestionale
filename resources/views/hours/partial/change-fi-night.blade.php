<div contenteditable="false" data-hour="{{$hour->id}}" class="night cursor-pointer mx-0.5 text-xs font-medium px-1 py-0.5 rounded
            @if($hour->technical_report_hour()->nightEU) bg-blue-300 text-blue-800 dark:bg-blue-200 dark:text-blue-800
            @elseif($hour->technical_report_hour()->nightExtraEU) bg-green-300 text-green-800 dark:bg-green-200 dark:text-green-900
            @else bg-yellow-200 text-yellow-800 dark:bg-yellow-200 dark:text-yellow-900
            @endif ">
    @if($hour->technical_report_hour()->nightEU)
        EU
    @elseif($hour->technical_report_hour()->nightExtraEU)
        XEU
    @else
        NO
    @endif
</div>
