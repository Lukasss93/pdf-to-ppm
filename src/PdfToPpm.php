<?php


namespace Lukasss93\PdfToPpm;

use Lukasss93\PdfToPpm\Drivers\PdfToPpmDriver;
use Lukasss93\PdfToPpm\Exceptions\InvalidDirectory;
use Lukasss93\PdfToPpm\Exceptions\InvalidFormat;
use Lukasss93\PdfToPpm\Exceptions\PageDoesNotExist;
use Psr\Log\LoggerInterface;

class PdfToPpm
{
    /** @var PdfToPpmDriver */
    protected $driver;

    /** @var string */
    protected $pdf;

    /** @var int */
    protected $page = 1;

    /** @var int */
    protected $numberOfPages;

    /** @var string|null */
    protected $outputFormat;

    /** @var string[] */
    protected $validOutputFormats = ['ppm', 'jpg', 'png', 'tif'];

    /** @var int */
    protected $resolution = 144;

    /** @var bool */
    protected $gray = false;

    /** @var int|null */
    protected $scale;

    /** @var string[] */
    protected $options = [];

    /**
     * PdfToPpm constructor.
     *
     * @param PdfToPpmDriver $driver
     */
    public function __construct(PdfToPpmDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Create a PdfToPpm instance.
     *
     * @param array $configuration
     * @param LoggerInterface|null $logger
     *
     * @return static
     */
    public static function create($configuration = [], LoggerInterface $logger = null): self
    {
        return new static(PdfToPpmDriver::create($configuration, $logger));
    }

    /**
     * Returns the total number of pages in the pdf.
     *
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    /**
     * Set a pdf path file.
     *
     * @param string $pdf
     *
     * @return PdfToPpm
     */
    public function setPdf(string $pdf): self
    {
        $this->pdf = $pdf;

        $this->numberOfPages = PdfInfo::create()
            ->setPdf($this->pdf)
            ->get()
            ->getPages();

        return $this;
    }

    /**
     * Set the pdf page.
     *
     * @param int $page
     *
     * @return PdfToPpm
     *
     * @throws PageDoesNotExist
     */
    public function setPage(int $page): self
    {
        if ($page < 1 || $page > $this->getNumberOfPages()) {
            throw new PageDoesNotExist("Page {$page} does not exist");
        }

        $this->page = $page;
        return $this;
    }

    /**
     * Set the output image format.
     *
     * @param string $outputFormat
     *
     * @return PdfToPpm
     *
     * @throws InvalidFormat
     */
    public function setOutputFormat(string $outputFormat): PdfToPpm
    {
        if (!$this->isValidOutputFormat($outputFormat)) {
            throw new InvalidFormat("Format '{$outputFormat}' is not supported");
        }

        $this->outputFormat = $outputFormat;
        return $this;
    }

    /**
     * Set the output image resolution.
     *
     * @param int $resolution
     *
     * @return PdfToPpm
     */
    public function setResolution(int $resolution): self
    {
        $this->resolution = $resolution;
        return $this;
    }

    /**
     * Set the output image color.
     * False for colors, True for grayscale.
     *
     * @param bool $gray
     *
     * @return PdfToPpm
     */
    public function setGray(bool $gray): self
    {
        $this->gray = $gray;
        return $this;
    }

    /**
     * Scales each page to fit within scale-to*scale-to pixel box.
     *
     * @param int $scale
     *
     * @return $this
     */
    public function setScale(int $scale): self
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * Replace all options and set them by hand.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Save the current pdf page as image.
     * @param string $pathToImage
     * @param string $prefix
     *
     * @return string
     *
     * @throws InvalidFormat
     */
    public function saveImage(string $pathToImage, string $prefix = ''): string
    {
        if (is_dir($pathToImage)) {
            $pathToImage = rtrim($pathToImage, '\/').DIRECTORY_SEPARATOR.$prefix.$this->page;

            $this->setOutputFormat($this->determineOutputFormat($pathToImage.'.'.$this->outputFormat));
        } else {
            $info = pathinfo($pathToImage);
            $basePath = $info['dirname'].DIRECTORY_SEPARATOR.$info['filename'];

            if ($this->outputFormat === null) {
                $this->setOutputFormat($this->determineOutputFormat($pathToImage));
            }
            $pathToImage = $basePath;
        }

        //init command
        $command = [
            //input file
            $this->pdf,

            //output dir
            $pathToImage,
        ];

        if (count($this->options) > 0) {
            $command += $this->options;
        } else {
            $command += [
                //output resolution
                '-r', $this->resolution,

                //output page
                '-f', $this->page,
                '-l', $this->page,

                //no page suffix
                '-singlefile'
            ];

            //set output format
            if ($this->outputFormat !== 'ppm') {
                $command[] = '-'.$this->getCorrectFormat($this->outputFormat);
            }

            //set gray output
            if ($this->gray) {
                $command[] = '-gray';
            }
        }

        $this->driver->command($command);

        return $pathToImage.'.'.$this->outputFormat;
    }

    /**
     * Save all pdf pages as images.
     *
     * @param string $directory
     * @param string $prefix
     *
     * @return string[]
     *
     * @throws InvalidDirectory
     * @throws InvalidFormat
     * @throws PageDoesNotExist
     */
    public function saveAllPagesAsImages(string $directory, string $prefix = ''): array
    {
        if (!is_dir($directory)) {
            throw new InvalidDirectory('The $directory parameter is an invalid directory');
        }

        $numberOfPages = $this->getNumberOfPages();

        if ($numberOfPages === 0) {
            return [];
        }

        if ($this->outputFormat === null) {
            $this->setOutputFormat('jpg');
        }

        return array_map(function ($pageNumber) use ($directory, $prefix) {
            $this->setPage($pageNumber);

            $destination = rtrim($directory, '\/')."/{$prefix}{$pageNumber}.{$this->outputFormat}";

            $this->saveImage($destination);

            return $destination;
        }, range(1, $numberOfPages));
    }

    /**
     * Returns a valid output format from a path.
     *
     * @param string $pathToImage
     *
     * @return string
     *
     * @throws InvalidFormat
     */
    protected function determineOutputFormat(string $pathToImage): string
    {
        $outputFormat = pathinfo($pathToImage, PATHINFO_EXTENSION);

        if (!empty($this->outputFormat)) {
            $outputFormat = $this->outputFormat;
        }

        $outputFormat = strtolower($outputFormat);

        if (!$this->isValidOutputFormat($outputFormat)) {
            return 'jpg';
        }

        return $outputFormat;
    }

    /**
     * Check if a format is valid.
     *
     * @param string $outputFormat
     *
     * @return bool
     */
    protected function isValidOutputFormat(string $outputFormat): bool
    {
        return in_array($outputFormat, $this->validOutputFormats, true);
    }

    protected function getCorrectFormat(string $format): string
    {
        switch ($format) {
            case 'jpg':
            case 'jpeg':
                return 'jpeg';
            case 'tif':
            case 'tiff':
                return 'tiff';
            default:
                return $format;
        }
    }

    public function version(): string
    {
        return $this->driver->getVersion();
    }
}