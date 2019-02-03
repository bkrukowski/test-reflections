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

namespace PhpHeaderFile\Exporters;

use PhpHeaderFile\BaseTest;
use PhpHeaderFile\View\TwigFactory;

/**
 * @covers \PhpHeaderFile\Exporters\CodeExporter
 *
 * @internal
 */
final class CodeExporterTest extends BaseTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::requireXdebug();
    }

    /**
     * @dataProvider providerAll
     *
     * @param null|\ReflectionExtension $extension
     * @param null|\ReflectionClass     $class
     * @param null|\ReflectionFunction  $function
     * @param null|string               $constant
     * @param null|string[]             $list
     * @param bool                      $canBeExported
     */
    public function testAll(?\ReflectionExtension $extension, ?\ReflectionClass $class, ?\ReflectionFunction $function, ?string $constant, ?array $list, bool $canBeExported): void
    {
        $exporter = new CodeExporter((new TwigFactory())->create());

        if (null !== $extension) {
            $exporter->addExtension($extension);
        }

        if (null !== $class) {
            $exporter->addClass($class);
        }

        if (null !== $function) {
            $exporter->addFunction($function);
        }

        if (null !== $constant) {
            $exporter->addConstants($constant);
        }

        $this->assertSame($canBeExported, $exporter->hasThingsToExport());

        $exportedList = $exporter->listExportedThings();
        if (!\is_array($exportedList)) {
            $exportedList = \iterator_to_array($exportedList, false);
        }

        foreach ($list ?? [] as $item) {
            $this->assertContains($item, $exportedList);
        }

        $this->assertInternalType('string', $exporter->export());
    }

    public function providerAll(): iterable
    {
        yield 'ext-xdebug' => [
            new \ReflectionExtension('xdebug'),
            null,
            null,
            null,
            ['fn-xdebug_enable', 'fn-xdebug_var_dump'],
            true,
        ];

        yield 'fn-xdebug_var_dump' => [
            null,
            null,
            new \ReflectionFunction('xdebug_var_dump'),
            null,
            ['fn-xdebug_var_dump'],
            true,
        ];

        yield 'const-XDEBUG_CC_DEAD_CODE' => [
            null,
            null,
            null,
            'XDEBUG_CC_DEAD_CODE',
            ['const-XDEBUG_CC_DEAD_CODE'],
            true,
        ];

        yield 'class-DomainException' => [
            null,
            new \ReflectionClass('DomainException'),
            null,
            null,
            ['class-DomainException'],
            true,
        ];

        yield 'none' => [
            null,
            null,
            null,
            null,
            null,
            false,
        ];
    }
}
