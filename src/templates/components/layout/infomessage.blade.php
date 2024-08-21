<div class="inline-block w-full p-4 mb-4 border rounded
    @if($type === InfoMessageType::SUCCESS) bg-infomessage-success-background border-infomessage-success-border
    @elseif($type === InfoMessageType::ERROR) bg-infomessage-error-background border-infomessage-error-border
    @elseif($type === InfoMessageType::WARNING) bg-infomessage-warning-background border-infomessage-warning-border
    @elseif($type === InfoMessageType::INFO) bg-infomessage-info-background border-infomessage-info-border
    @else bg-infomessage-none-background border-infomessage-none-border
    @endif"
     data-message-type="{{ $type->getFormatted() }}"
>
    {{ $slot }}
</div>
