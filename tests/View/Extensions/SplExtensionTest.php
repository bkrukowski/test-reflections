<?php

declare(strict_types=1);

/*
 * This file is part of the php-header-file/php-header-file package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpHeaderFile\View\Extensions;

use PhpHeaderFile\Exporters\ExporterInterface;

/**
 * @covers \PhpHeaderFile\View\Extensions\AbstractExtension
 * @covers \PhpHeaderFile\View\Extensions\SplExtension
 *
 * @internal
 */
final class SplExtensionTest extends BaseTest
{
    /** @dataProvider providerVarExport
     * @param        $var
     * @param string $expected
     */
    public function testVarExport($var, string $expected): void
    {
        $this->assertSame($expected, $this->callMethod('varExport', $var));
    }

    public function providerVarExport(): iterable
    {
        return [
            [null, 'null'],
            [false, 'false'],
            [[], 'array()'],
        ];
    }

    /**
     * @dataProvider providerExcept
     *
     * @param iterable $array
     * @param string[] $excepts
     * @param string[] $expected
     */
    public function testExcept(iterable $array, array $excepts, array $expected): void
    {
        $this->assertSame(
            $expected,
            $this->callMethod('except', $array, $excepts)
        );
    }

    public function providerExcept(): iterable
    {
        return [
            [['foo', 'bar'], [], ['foo', 'bar']],
            [['foo', 'bar'], ['bar'], ['foo']],
            [new \ArrayIterator(['foo', 'bar']), ['bar'], ['foo']],
        ];
    }

    /**
     * @dataProvider providerEscapeType
     *
     * @param string $input
     * @param string $expected
     */
    public function testEscapeType(string $input, string $expected): void
    {
        $this->assertSame($expected, $this->callMethod('escapeType', $input));
    }

    public function providerEscapeType(): iterable
    {
        return [
            ['\\'.static::class, '\\'.static::class],
            [static::class, '\\'.static::class],
            ['bool', 'bool'],
            ['\\'.ExporterInterface::class, '\\'.ExporterInterface::class],
            [ExporterInterface::class, '\\'.ExporterInterface::class],
        ];
    }

    protected function getExpectedFilters(): array
    {
        return [
            'escapeType',
            'varExport',
            'except',
        ];
    }

    protected function createExtension(): AbstractExtension
    {
        return new SplExtension();
    }
}
