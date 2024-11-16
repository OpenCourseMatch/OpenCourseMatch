<?php

class PDF {
    private $pdf;
    private $documentTitle;

    public function __construct(
        User $creatingUser,
        string $title,
        string $template,
        array $data
    ) {
        $logoData = base64_encode(file_get_contents(__APP_DIR__ . "/src/static/img/logo.svg"));

        $formattedDate = (new DateTimeImmutable())->format(DateTimeInterface::RFC3339_EXTENDED);

        $creatorQrCode = new \chillerlan\QRCode\QRCode();
        $creatorQrCode->setOptions(self::getQrOptionsForPdf());
        $creatorQrCodeData = $creatorQrCode->render($formattedDate . PHP_EOL . $creatingUser->getId());

        $this->documentTitle = $title;

        $data["title"] = $title;
        $data["logoData"] = $logoData;
        $data["creatorQrCodeData"] = $creatorQrCodeData;

        $html = Blade->run($template, $data);

        $this->pdf = new \Dompdf\Dompdf();
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper("A4", "portrait");
        $this->pdf->render();
    }

    public function stream() {
        $this->pdf->stream($this->documentTitle . ".pdf", [
            "Attachment" => false
        ]);
    }

    public static function getQrOptionsForPdf(): \chillerlan\QRCode\QROptions {
        return new \chillerlan\QRCode\QROptions([
            "addQuietzone" => false
        ]);
    }
}
