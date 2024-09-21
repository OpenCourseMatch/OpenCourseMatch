<!DOCTYPE html>
<html>
<head>
    <title>Benutzerinformationen</title>
    <style>
        @page {
            margin: 4.5cm 1.5cm;
        }

        header {
            position: fixed;
            top: -3cm;
            left: 0;
            right: 0;
            background-color: lightblue;
            height: 3cm;
        }

        footer {
            position: fixed;
            bottom: -3cm;
            left: 0;
            right: 0;
            background-color: lightblue;
            height: 2cm;
        }
    </style>
</head>

<body>
    <header>
        <table style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
            <tr style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
                <td style="border-collapse: collapse; border: none; margin: 0; padding: 0; height: 5.5em; width: 7em; vertical-align: top;">
                    <img src="data:image/svg+xml;base64, {!! $logoSrc !!}" alt="Logo" style="width: 4.640625em; height: 3.28125em; margin: 0; padding: 0;">
                </td>
                <td style="border-collapse: collapse; border: none; margin: 0; padding: 0;">
                    <h1 style="margin: 0;">
                        {{ Config::$APP_SETTINGS["APP_NAME"] }}
                    </h1>
                    <p style="margin: 0;">
                        {{ t("User information") }}
                    </p>
                </td>
            </tr>
        </table>
        <hr style="margin-top: 1em; border: none; background-color: black;">
    </header>

    <img src="{{ $creatorQrCodeData }}">

    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>

    <img src="{{ $loginQrCodeData }}">

    <footer>
        <hr style="border: none; background-color: black;">
        <h2 style="margin: 0;">
            {{ Config::$APP_SETTINGS["APP_NAME"] }}
        </h2>
        <p style="margin: 0;">
            Licensed under the MIT License.
        </p>
    </footer>
</body>
