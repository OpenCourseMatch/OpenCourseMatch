@if(count($assignment["courseLeaders"]) > 0)
    <h3>
        {{ t("Course leaders") }}
    </h3>

    <table style="">
        <tr style="">
            <td style="border: 1px solid gray;">
                {{ t("Username") }}:
            </td>
            <td style="border: 1px solid gray;">
            </td>
        </tr>
        <tr style="">
            <td style="border: 1px solid gray;">
                {{ t("Password") }}:
            </td>
            <td style="border: 1px solid gray;">

            </td>
        </tr>
    </table>
@endif
