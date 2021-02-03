<?php

namespace Lukasss93\PdfToPpm\Drivers;

use Alchemy\BinaryDriver\AbstractBinary;
use Alchemy\BinaryDriver\Configuration;
use Alchemy\BinaryDriver\ConfigurationInterface;
use Alchemy\BinaryDriver\Exception\ExecutableNotFoundException;
use Psr\Log\LoggerInterface;
use RuntimeException;

class PdfToPpmDriver extends AbstractBinary
{
    /**
     * Returns the name of the driver
     *
     * @return string
     */
    public function getName(): string
    {
        return 'pdftoppm';
    }

    /**
     * Creates the pdftoppm wrapper
     *
     * @param array|ConfigurationInterface $configuration The configuration
     * @param LoggerInterface|null $logger A Logger
     *
     * @return static
     *
     * @throws ExecutableNotFoundException In case none of the binaries were found
     */
    public static function create($configuration = [], LoggerInterface $logger = null): self
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        $binaries = $configuration->get('pdftoppm.binaries', ['pdftoppm']);

        return static::load($binaries, $logger, $configuration);
    }

    /**
     * Returns the version of the driver
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function getVersion(): string
    {
        $valid = preg_match('/pdftoppm version (.*)/', $this->command('-v'), $version);
        if ($valid === false || !isset($version[1])) {
            throw new RuntimeException('Cannot to parse the pdftoppm version!');
        }

        return $version[1];
    }
}