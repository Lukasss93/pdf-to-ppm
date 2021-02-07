<?php

namespace Lukasss93\PdfToPpm;

use Lukasss93\PdfToPpm\Drivers\PdfInfoDriver;
use Psr\Log\LoggerInterface;
use RuntimeException;

class PdfInfo
{
    /** @var PdfInfoDriver */
    protected $driver;

    /** @var string */
    protected $pdf;
    
    public function __construct(PdfInfoDriver $driver)
    {
        $this->driver = $driver;
    }

    public static function create($configuration = [], LoggerInterface $logger = null): self
    {
        return new static(PdfInfoDriver::create($configuration, $logger));
    }

    /**
     * @param string $pdf
     * @return PdfInfo
     */
    public function setPdf(string $pdf): self
    {
        $this->pdf = $pdf;
        return $this;
    }

    public function get(): PdfInfoData
    {
        $pattern = '/(?<key>.*): +(?<value>.*)/';
        $input = $this->driver->command($this->pdf);

        $valid = preg_match_all($pattern, $input, $info, PREG_SET_ORDER);
        if ($valid === false || count($info) === 0) {
            throw new RuntimeException('Cannot to parse the pdfinfo data!');
        }

        $data = [];
        foreach ($info as $item) {
            $data[$item['key']] = $item['value'];
        }

        return new PdfInfoData($data);
    }

    public function version(): string
    {
        return $this->driver->getVersion();
    }
}