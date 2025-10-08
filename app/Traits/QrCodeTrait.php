<?php

namespace App\Traits;

use Endroid\QrCode\Builder\Builder as BuilderBuilder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel as QrCodeErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

trait QrCodeTrait
{
    public static function generate($data)
    {
        $builder = new BuilderBuilder(
            writer: new PngWriter,
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: QrCodeErrorCorrectionLevel::High,
            size: 100,
            margin: 5,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        $result = $builder->build();

        return $result;
    }
}
