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
use PhpHeaderFile\Pattern\PatternCompiler;
use PhpHeaderFile\View\TwigFactory;

/**
 * @covers \PhpHeaderFile\Exporters\CodeExporterDecorator
 *
 * @internal
 */
final class CodeExporterDecoratorTest extends BaseTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::requireXdebug();
    }

    /**
     * @dataProvider providerAll
     *
     * @param string[]      $patterns
     * @param null|string[] $list
     * @param bool          $canBeExported
     */
    public function testAll(array $patterns, ?array $list, bool $canBeExported)
    {
        $exporter = new CodeExporter((new TwigFactory())->create());
        $decorator = new CodeExporterDecorator(new PatternCompiler());

        $decorator->decorate($exporter, ...$patterns);

        $exportedList = $exporter->listExportedThings();
        if (!\is_array($exportedList)) {
            $exportedList = \iterator_to_array($exportedList, false);
        }

        $this->assertSame($canBeExported, $exporter->hasThingsToExport());
        foreach ($list ?? [] as $item) {
            $this->assertContains($item, $exportedList);
        }
    }

    public function providerAll(): iterable
    {
        return [
            'ext-xdebug' => [
                ['ext-xdebug'],
                ['fn-xdebug_enable', 'fn-xdebug_var_dump', 'ext-xdebug'],
                true,
            ],
            'ext-xdebu*' => [
                ['ext-xdebu*'],
                ['fn-xdebug_enable', 'fn-xdebug_var_dump', 'ext-xdebug'],
                true,
            ],
            'fn-xdebug_var_dump' => [
                ['fn-xdebug_var_dump'],
                ['fn-xdebug_var_dump'],
                true,
            ],
            'fn-xdebug_*_dump' => [
                ['fn-xdebug_*_dump'],
                ['fn-xdebug_var_dump'],
                true,
            ],
            'const-XDEBUG_CC_BRANCH_CHECK' => [
                ['const-XDEBUG_CC_BRANCH_CHECK'],
                ['const-XDEBUG_CC_BRANCH_CHECK'],
                true,
            ],
            'const-XDEBUG_CC_*' => [
                ['const-XDEBUG_CC_*'],
                ['const-XDEBUG_CC_BRANCH_CHECK', 'const-XDEBUG_CC_DEAD_CODE', 'const-XDEBUG_CC_UNUSED'],
                true,
            ],
            'class-LengthException' => [
                ['class-LengthException'],
                ['class-LengthException'],
                true,
            ],
            'class-RuntimeExcept*' => [
                ['class-RuntimeExcept*'],
                ['class-RuntimeException'],
                true,
            ],
            'class-@invalidClassName' => [
                ['class-@invalidClassName'],
                null,
                false,
            ],
        ];
    }
}
