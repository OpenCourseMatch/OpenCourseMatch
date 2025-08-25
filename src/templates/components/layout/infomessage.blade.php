<div class="inline-block w-full p-4 mb-4 border rounded
    @if($type === \struktal\InfoMessage\InfoMessageType::SUCCESS) bg-safe-200 border-safe-500 text-safe-900
    @elseif($type === \struktal\InfoMessage\InfoMessageType::ERROR) bg-danger-200 border-danger-500 text-danger-900
    @elseif($type === \struktal\InfoMessage\InfoMessageType::WARNING) bg-warning-200 border-warning-500 text-warning-900
    @elseif($type === \struktal\InfoMessage\InfoMessageType::INFO) bg-info-200 border-info-500 text-info-900
    @else bg-surface-200 border-surface-500
    @endif"
     data-message-type="{{ $type->getName() }}"
>
    {{ $slot }}
</div>
