<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class QrCodeController extends Controller
{
    /**
     * Redirect to the URI associated with the QR code.
     */
    public function redirect(QrCode $qrCode): RedirectResponse
    {
        abort_if(! $qrCode->is_active, 404);

        // Registrar el escaneo
        $qrCode->scans()->create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'scanned_at' => now(),
        ]);

        return redirect()->away($qrCode->uri);
    }

    /**
     * Generate and download the QR code image.
     */
    public function download(QrCode $qrCode): Response
    {
        $qrCodeBuilder = new Builder(
            writer: new PngWriter,
            writerOptions: [],
            validateResult: false,
            data: route('qr.redirect', $qrCode),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = $qrCodeBuilder->build();

        return response(
            $result->getString(),
            200,
            ['Content-Type' => $result->getMimeType()]
        );
    }
}
