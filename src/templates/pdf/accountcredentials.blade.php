@component("pdf.components.pdfshell")
    @foreach($accounts as $i => $account)
        <h2>
            {{ t("Account credentials for \$\$name\$\$", [
                "name" => $account->getFullName()
            ]) }}
        </h2>

        @include("pdf.components.accountcredentials", [
            "account" => $account,
            "password" => $passwords[$account->getId()] ?? null,
            "loginQrCodeData" => $loginQrCodeData
        ])

        @if($i < count($accounts) - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
@endcomponent
