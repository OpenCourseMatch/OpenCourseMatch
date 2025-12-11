<div class="flex flex-col w-full p-4 gap-2 rounded rounded-lg border border-2
     @if(!isset($scheme) || $scheme === BoxScheme::SURFACE) bg-surface-200 border-surface-500
     @elseif($scheme === BoxScheme::PRIMARY) bg-primary-200 border-primary-500
     @elseif($scheme === BoxScheme::SECONDARY) bg-secondary-200 border-secondary-500
     @elseif($scheme === BoxScheme::DANGER) bg-danger-200 border-danger-500
     @elseif($scheme === BoxScheme::WARNING) bg-warning-200 border-warning-500
     @elseif($scheme === BoxScheme::INFO) bg-info-200 border-info-500
     @endif">
    {!! $slot !!}
</div>
