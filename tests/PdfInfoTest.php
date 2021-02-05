<?php

namespace Lukasss93\PdfToPpm\Test;

use Lukasss93\PdfToPpm\PdfInfo;

class PdfInfoTest extends TestCase
{
    protected $binary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->binary = PdfInfo::create([
            'pdfinfo.binaries' => $_ENV['PDFINFO_BINARY_PATH']
        ]);
    }

    /**
     * @test
     * @dataProvider providerPdfs
     * @param string $path
     */
    public function it_get_the_pdf_informations(string $path): void
    {
        $info = $this->binary
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
        self::assertIsStringOrNull($info->getPdfVersion());

        self::assertIsArray($info->toArray());
    }

    /**
     * @test
     */
    public function it_get_the_driver_version(): void
    {
        $version = $this->binary->version();
        self::assertIsString($version);
    }
}