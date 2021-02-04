<?php

namespace Lukasss93\PdfToPpm\Test;

use Lukasss93\PdfToPpm\PdfInfo;

class PdfInfoTest extends TestCase
{
    /**
     * @test
     * @dataProvider providerPdfs
     * @param string $path
     */
    public function it_get_the_pdf_informations(string $path): void
    {
        $info = PdfInfo::create()
            ->setPdf($path)
            ->get();

        self::assertIsStringOrNull($info->getTitle());
        self::assertIsStringOrNull($info->getCreator());
        self::assertIsStringOrNull($info->getProducer());
        self::assertIsDateTimeOrNull($info->getCreationDate());
        self::assertIsDateTimeOrNull($info->getModDate());
        self::assertIsStringOrNull($info->getTagged());
        self::assertIsStringOrNull($info->getUserProperties());
        self::assertIsStringOrNull($info->getSuspects());
        self::assertIsStringOrNull($info->getForm());
        self::assertIsStringOrNull($info->getJavascript());
        self::assertIsIntOrNull($info->getPages());
        self::assertIsStringOrNull($info->getEncrypted());
        self::assertIsStringOrNull($info->getPageSize());
        self::assertIsStringOrNull($info->getPageRot());
        self::assertIsStringOrNull($info->getFileSize());
        self::assertIsStringOrNull($info->getOptimized());

        self::assertIsArray($info->toArray());
    }

    /**
     * @test
     */
    public function it_get_the_driver_version(): void
    {
        $version = PdfInfo::create()->version();
        self::assertIsString($version);
    }
}