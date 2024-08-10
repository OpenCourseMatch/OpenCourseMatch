<div class="infomessage-list flex flex-col">
    @foreach(InfoMessage::getMessages() as $message)
        @component("components.layout.infomessage", [
            "type" => $message->getType()
        ])
            {{ $message->getMessage() }}
        @endcomponent
    @endforeach
</div>
