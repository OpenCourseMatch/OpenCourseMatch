{{--
    @param DateTime|DateTimeImmutable $date
    @param bool $showTime = true
    @param bool $showDate = true
    @param bool $monthAsText = false
    @param bool $hideSeconds = true
    @param bool $showWeekday = false
--}}
<time datetime="{{ $date->format(DateTimeInterface::RFC3339_EXTENDED) }}"
    @if(isset($showTime) && !$showTime) data-show-time="false" @else data-show-time="true" @endif
    @if(isset($showDate) && !$showDate) data-show-date="false" @else data-show-date="true" @endif
    @if(isset($monthAsText) && $monthAsText) data-month-as-text="true" @else data-month-as-text="false" @endif
    @if(isset($hideSeconds) && !$hideSeconds) data-hide-seconds="false" @else data-hide-seconds="true" @endif
    @if(isset($showWeekday) && $showWeekday) data-show-weekday="true" @else data-show-weekday="false" @endif>
    @if(isset($hideSeconds) && !$hideSeconds)
        {{ $date->format("Y-m-d H:i") }} UTC
    @else
        {{ $date->format("Y-m-d H:i:s") }} UTC
    @endif
</time>
