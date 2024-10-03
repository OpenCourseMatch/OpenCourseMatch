@component("pdf.pdfshell")
    <h2>
        {{ t("Account credentials for") }} {{ $account->getFullName() }}
    </h2>

    {{-- TODO: Could remove the padding here... --}}
    <table>
        <tr>
            <td style="padding: 0;">
                {{ t("Username") }}:
            </td>
            <td style="padding: 0;">
                <span style="font-family: monospace; font-weight: bolder;">
                    {{ $account->getUsername() }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding: 0;">
                {{ t("Password") }}:
            </td>
            <td style="padding: 0;">
                @if($password !== null)
                    <span style="font-family: monospace; font-weight: bolder;">
                        {{ $password }}
                    </span>
                @else
                    {{ t("Not changed") }}
                @endif
            </td>
        </tr>
    </table>

    {{ t("It's recommended to change the initial password after your first login. To do that, navigate to the account settings.") }}
    <br><br>
    {{ t("Log in to your account by scanning this QR code") }}:
    <br><br>
    <img src="{{ $loginQrCodeData }}" alt="Login QR code" style="width: 25%;">

    {{-- TODO: More user information... Group, chosen projects, etc. --}}
@endcomponent
