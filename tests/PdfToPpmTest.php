<?php

namespace Lukasss93\PdfToPpm\Test;

use Lukasss93\PdfToPpm\Exceptions\InvalidDirectory;
use Lukasss93\PdfToPpm\Exceptions\InvalidFormat;
use Lukasss93\PdfToPpm\Exceptions\PageDoesNotExist;
use Lukasss93\PdfToPpm\PdfToPpm;

class PdfToPpmTest extends TestCase
{
    protected $binary;

    protected function setUp(): void
    {
        parent::setUp();
        $this->binary = PdfToPpm::create([
            'pdftoppm.binaries' => $_ENV['PDFTOPPM_BINARY_PATH']
        ]);
    }

    /**
     * @test
     * @param string $pdf
     * @param int $pages
     * @dataProvider providerNumberOfPages
     */
    public function it_get_the_number_of_pages(string $pdf, int $pages): void
    {
        $value = $this->binary
            ->setPdf($pdf)
            ->getNumberOfPages();

        self::assertEquals($pages, $value);
    }

    /**
     * @test
     * @param string $pdf
     * @param string $image
     * @throws InvalidFormat
     * @dataProvider providerSaveImage
     */
    public function it_save_image(string $pdf, string $image): void
    {
        $path = $this->binary
            ->setPdf($pdf)
            ->saveImage($image);
        
        self::assertEquals($image, $path);
        self::assertFileExists($image);
    }
    
    /**
     * @test
     * @throws InvalidFormat
     */
    public function it_save_image_with_different_resolution(): void
    {
        $path50 = $this->binary
            ->setPdf(dirname(__DIR__) . '/tests/files/sample-4.pdf')
            ->setResolution(50)
            ->saveImage(dirname(__DIR__) . '/tests/images/image-resolution-50.jpg');
        
        $path500 = $this->binary
            ->setPdf(dirname(__DIR__) . '/tests/files/sample-4.pdf')
            ->setResolution(500)
            ->saveImage(dirname(__DIR__) . '/tests/images/image-resolution-500.jpg');
        
        self::assertFileExists($path50);
        self::assertFileExists($path500);
        self::assertNotEquals(filesize($path50), filesize($path500));
    }
    
    /**
     * @test
     * @param string $pdf
     * @param string $prefix
     * @throws InvalidFormat
     * @throws InvalidDirectory
     * @throws PageDoesNotExist
     * @dataProvider providerSaveAllPagesAsImages
     */
    public function it_save_all_pages_as_images(string $pdf, string $prefix): void
    {
        $paths = $this->binary
            ->setPdf($pdf)
            ->saveAllPagesAsImages(dirname(__DIR__).'/tests/images/', $prefix);

        foreach ($paths as $i => $path) {
            $image = dirname(__DIR__).'/tests/images/'.$prefix.($i + 1).'.jpg';
            self::assertEquals($image, $path);
            self::assertFileExists($image);
        }
    }

    /**
     * @test
     */
    public function it_get_the_driver_version(): void
    {
        $version = $this->binary->version();
        self::assertIsString($version);
    }

    //region PROVIDERS

    public function providerNumberOfPages(): array
    {
        return [
            'sample-4'            => [dirname(__DIR__).'/tests/files/sample-4.pdf', 4],
            'sample-30'           => [dirname(__DIR__).'/tests/files/sample-30.pdf', 30],
        ];
    }

    public function providerSaveImage(): array
    {
        return [
            'sample-4'            => [
                dirname(__DIR__).'/tests/files/sample-4.pdf',
                dirname(__DIR__).'/tests/images/sample-4-first-page.jpg',
            ],
            'sample-30'           => [
                dirname(__DIR__).'/tests/files/sample-30.pdf',
                dirname(__DIR__).'/tests/images/sample-30-first-page.jpg',
            ],
        ];
    }

    public function providerSaveAllPagesAsImages(): array
    {
        return [
            'sample-4'            => [dirname(__DIR__).'/tests/files/sample-4.pdf', 'sample-4-'],
            'sample-30'           => [dirname(__DIR__).'/tests/files/sample-30.pdf', 'sample-30-'],
        ];
    }

    //endregion
}