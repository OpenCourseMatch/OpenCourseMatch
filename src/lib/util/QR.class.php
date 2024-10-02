<?php

class QR {
    public static function loginQrCode(): string {
        $code = new \chillerlan\QRCode\QRCode();
        $code->setOptions(PDF::getQrOptionsForPdf());
        return $code->render(Config::$APP_SETTINGS["APP_URL"]);
    }
}
