<?php

namespace Lukasss93\PdfToPpm\Test;

use DateTime;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public static function assertIsStringOrNull($value): void
    {
        self::assertThat($value, self::logicalOr(self::isType(IsType::TYPE_STRING), self::isNull()));
    }

    public static function assertIsIntOrNull($value): void
    {
        self::assertThat($value, self::logicalOr(self::isType(IsType::TYPE_INT), self::isNull()));
    }

    public static function assertIsDateTime($value): void
    {
        self::assertInstanceOf(DateTime::class, $value);
    }

    public static function assertIsDateTimeOrNull($value): void
    {
        self::assertThat($value, self::logicalOr(self::isInstanceOf(DateTime::class), self::isNull()));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $files = glob(dirname(__DIR__).'/tests/images/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}