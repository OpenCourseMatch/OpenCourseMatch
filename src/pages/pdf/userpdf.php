<?php

use Dompdf\Dompdf;

$userId = intval($_GET["user"]);

$user = User::dao()->getObject([
    "id" => $userId
]);

$logoSrc = base64_encode(file_get_contents(__APP_DIR__ . "/src/static/img/logo.svg"));

$qrOptions = new \chillerlan\QRCode\QROptions([
    "addQuietzone" => false
]);


$loginQrCode = new \chillerlan\QRCode\QRCode();
$loginQrCode->setOptions($qrOptions);
$loginQrCodeData = $loginQrCode->render(Config::$APP_SETTINGS["APP_URL"]);

$creatorQrCode = new \chillerlan\QRCode\QRCode();
$creatorQrCode->setOptions($qrOptions);
$creatorQrCodeData = $creatorQrCode->render(DateFormatter::technicalDateTime() . PHP_EOL . $user->getId());

$html = Blade->run("pdf.userpdf", [
    "user" => $user,
    "logoSrc" => $logoSrc,
    "loginQrCodeData" => $loginQrCodeData,
    "creatorQrCodeData" => $creatorQrCodeData
]);

//echo $creatorQrCodeData;

//echo $html;
//exit;

$pdf = new Dompdf();
$options = $pdf->getOptions();
$options->setIsRemoteEnabled(true);
$pdf->setOptions($options);
$pdf->loadHtml($html);
$pdf->setPaper("A4", "portrait");
$pdf->render();
$pdf->stream("dompdf_out.pdf", array("Attachment" => false));



exit(0);
