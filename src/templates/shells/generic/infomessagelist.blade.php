<div class="infomessage-list flex flex-col">
    @foreach(InfoMessage->getMessages() as $message)
        @component("shells.generic.infomessage", [
            "type" => $message->getType()
        ])
            {{ $message->getMessage() }}
        @endcomponent
    @endforeach
</div>
