<table style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
    <tr style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
        <td style="padding-left: 0;">
            {{ t("Username") }}:
        </td>
        <td style="padding-left: 1em;">
            <span style="font-family: monospace; font-weight: bolder;">
                {{ $account->getUsername() }}
            </span>
        </td>
    </tr>
    <tr style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
        <td style="padding: 0;">
            {{ t("Password") }}:
        </td>
        <td style="padding-left: 1em;">
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
