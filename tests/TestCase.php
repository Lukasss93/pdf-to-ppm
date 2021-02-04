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

    public function providerPdfs(): array
    {
        return [
            'sample-4'            => [dirname(__DIR__).'/tests/files/sample-4.pdf'],
            'sample-30'           => [dirname(__DIR__).'/tests/files/sample-30.pdf'],
            'sample-corrupted-92' => [dirname(__DIR__).'/tests/files/sample-corrupted-92.pdf'],
        ];
    }
}