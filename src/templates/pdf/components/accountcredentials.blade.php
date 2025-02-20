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

<br>
@if($password !== null)
    {{ t("It's recommended to change the initial password after your first login. To do that, navigate to the account settings.") }}
    <br><br>
@endif

@if($account->getPermissionLevel() === PermissionLevel::USER->value)
    <table style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
        <tr style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
            <td style="padding: 0;">
                {{ t("Group") }}:
            </td>
            <td style="padding-left: 1em;">
                @if($account->getGroup() !== null)
                    <span style="font-family: monospace; font-weight: bolder;">
                        {{ $account->getGroup()->getName() }}
                    </span>
                    ({{ $account->getGroup()->getClearance() }})
                @else
                    {{ t("Default group") }} (0)
                @endif
            </td>
        </tr>

        <tr style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
            <td style="padding: 0;">
                {{ t("Leading course") }}:
            </td>
            <td style="padding-left: 1em;">
                @if($account->getLeadingCourse() !== null)
                    <span style="font-family: monospace; font-weight: bolder;">
                            {{ $account->getLeadingCourse()->getTitle() }}
                        </span>
                    @if($account->getLeadingCourse()->getOrganizer() !== null)
                        ({{ $account->getLeadingCourse()->getOrganizer() }})
                    @endif
                @else
                    -
                @endif
            </td>
        </tr>

        <tr style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
            <td style="padding: 0;">
                {{ t("Chosen courses") }}:
            </td>
            <td style="padding-left: 1em;">
                @foreach($account->getChoices() as $i => $choice)
                    @if($choice !== null)
                        @if($i > 0)
                            <br>
                        @endif
                        {{ $i + 1 }}:
                        <span style="font-family: monospace; font-weight: bolder;">
                            {{ $choice->getCourse()->getTitle() }}
                        </span>
                        @if($choice->getCourse()->getOrganizer() !== null)
                            ({{ $choice->getCourse()->getOrganizer() }})
                        @endif
                    @else
                        @if($i > 0)
                            <br>
                        @endif
                        {{ $i + 1 }}: -
                    @endif
                @endforeach
            </td>
        </tr>

        <tr style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
            <td style="padding: 0;">
                {{ t("Assigned course") }}:
            </td>
            <td style="padding-left: 1em;">
                @if($account->getAssignedCourse() !== null)
                    <span style="font-family: monospace; font-weight: bolder;">
                        {{ $account->getAssignedCourse()->getTitle() }}
                    </span>
                    @if($account->getAssignedCourse()->getOrganizer() !== null)
                        ({{ $account->getAssignedCourse()->getOrganizer() }})
                    @endif
                @else
                    -
                @endif
            </td>
        </tr>
    </table>
@endif
<br><br>
{{ t("Log in to your account by scanning this QR code") }}:
<br><br>
<img src="{{ $loginQrCodeData }}" alt="Login QR code" style="width: 25%;">
