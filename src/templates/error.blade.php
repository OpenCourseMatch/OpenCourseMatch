@component("components.layout.appshell")
    <h1>
        {{ $code }}
    </h1>
    @if($code === 400)
        <p>
            {{ t("There was an error with your request.") }}
        </p>
    @elseif($code === 404)
        <p>
            {{ t("The requested resource could not be found.") }}
        </p>
    @elseif($code === 500)
        <p>
            {{ t("The server encountered an internal error and could not complete your request.") }}
        </p>
    @endif
@endcomponent
