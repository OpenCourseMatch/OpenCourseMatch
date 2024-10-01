<?php

use Dompdf\Dompdf;

$userId = intval($_GET["user"]);

$user = User::dao()->getObject([
    "id" => $userId
]);

$qrOptions = PDF::getQrOptionsForPdf();

$loginQrCode = new \chillerlan\QRCode\QRCode();
$loginQrCode->setOptions($qrOptions);
$loginQrCodeData = $loginQrCode->render(Config::$APP_SETTINGS["APP_URL"]);

$pdf = new PDF(
    $user,
    t("User details"),
    "pdf.userpdf",
    [
        "loginQrCodeData" => $loginQrCodeData
    ]
);
$pdf->stream();
