<?php

namespace Lukasss93\PdfToPpm\Drivers;

use Alchemy\BinaryDriver\AbstractBinary;
use Alchemy\BinaryDriver\Configuration;
use Alchemy\BinaryDriver\ConfigurationInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class PdfInfoDriver extends AbstractBinary
{
    /**
     * Returns the name of the driver
     *
     * @return string
     */
    public function getName(): string
    {
        return 'pdfinfo';
    }

    /**
     * Creates the pdftoppm wrapper
     *
     * @param array|ConfigurationInterface $configuration
     * @param LoggerInterface|null $logger
     *
     * @return static
     */
    public static function create($configuration = [], LoggerInterface $logger = null): self
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        $binaries = $configuration->get('pdfinfo.binaries', ['pdfinfo']);

        return static::load($binaries, $logger, $configuration);
    }

    /**
     * Returns the version of the driver
     *
     * @return string
     * @throws RuntimeException
     */
    public function getVersion(): string
    {
        $valid = preg_match('/pdfinfo version (.*)/', $this->command('-v'), $version);
        if ($valid === false || !isset($version[1])) {
            throw new RuntimeException('Cannot to parse the pdfinfo version!');
        }

        return $version[1];
    }
}