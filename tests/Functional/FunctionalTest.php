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

namespace PhpHeaderFile\Functional;

use PhpHeaderFile\BaseTest;
use PhpHeaderFile\Exporters\CodeExporter;
use PhpHeaderFile\Exporters\CodeExporterDecorator;
use PhpHeaderFile\Pattern\PatternCompiler;
use PhpHeaderFile\View\TwigFactory;
use Symfony\Component\Yaml\Yaml;

/**
 * @group functional
 *
 * @internal
 * @coversNothing
 */
final class FunctionalTest extends BaseTest
{
    public function testExportExtensions(): void
    {
        $this->requireXdebug();

        $exporter = $this->getDecoratedExporter(['ext-spl', 'ext-xdebug']);
        $things = $exporter->listExportedThings();
        if (!\is_array($things)) {
            $things = \iterator_to_array($things, false);
        }

        $this->assertInternalType('string', $exporter->export());
        $this->assertTrue($exporter->hasThingsToExport());

        $expectedThings = [
            'class-DomainException',
            'fn-class_implements',
            'ext-SPL',
            'ext-xdebug',
            'const-XDEBUG_CC_BRANCH_CHECK',
        ];
        foreach ($expectedThings as $thing) {
            $this->assertContains($thing, $things);
        }
    }

    /**
     * @dataProvider providerExport
     *
     * @param string[] $patterns
     * @param string   $expected
     */
    public function testExport(array $patterns, string $expected): void
    {
        $this->assertSame(
            $expected,
            $this->getDecoratedExporter($patterns)->export()
        );
    }

    public function providerExport(): iterable
    {
        foreach (Yaml::parse(\file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'tests.yaml')) as $key => $data) {
            yield $key => [
                $data['patterns'],
                \file_get_contents(\implode(\DIRECTORY_SEPARATOR, [__DIR__, 'tests', $data['result']])),
            ];
        }
    }

    private function getDecoratedExporter(array $patterns): CodeExporter
    {
        $exporter = new CodeExporter((new TwigFactory())->create());
        $decorator = new CodeExporterDecorator(new PatternCompiler());

        $decorator->decorate($exporter, ...$patterns);

        return $exporter;
    }
}
