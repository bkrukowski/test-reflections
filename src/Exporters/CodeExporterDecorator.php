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

use PhpHeaderFile\Pattern\PatternCompilerInterface;
use PhpHeaderFile\Pattern\PatternInterface;

final class CodeExporterDecorator
{
    /**
     * @var PatternCompilerInterface
     */
    private $compiler;

    /**
     * @param PatternCompilerInterface $compiler
     */
    public function __construct(PatternCompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    public function decorate(CodeExporter $codeExporter, string ...$patterns): void
    {
        foreach ($patterns as $rawPattern) {
            $pattern = $this->compiler->compile($rawPattern);

            if ($pattern->appliesToFunctions()) {
                $this->applyToFunctions($codeExporter, $pattern);
            }

            if ($pattern->appliesToClasses()) {
                $this->applyToClasses($codeExporter, $pattern);
            }

            if ($pattern->appliesToConstants()) {
                $this->applyToConstants($codeExporter, $pattern);
            }

            if ($pattern->appliesToExtensions()) {
                $this->applyToExtensions($codeExporter, $pattern);
            }
        }
    }

    private function applyToFunctions(CodeExporter $codeExporter, PatternInterface $pattern): void
    {
        $possibilities = $pattern->isWildcard()
            ? \array_merge(...\array_values(\get_defined_functions()))
            : (\function_exists($pattern->getPattern()) ? [$pattern->getPattern()] : []);

        foreach ($possibilities as $function) {
            if ($pattern->match($function)) {
                $codeExporter->addFunction(new \ReflectionFunction($function));
            }
        }
    }

    private function applyToClasses(CodeExporter $codeExporter, PatternInterface $pattern): void
    {
        $possibilities = $pattern->isWildcard()
            ? \array_merge(\get_declared_classes(), \get_declared_interfaces(), \get_declared_traits())
            : (
                \class_exists($pattern->getPattern()) || \interface_exists($pattern->getPattern()) || \trait_exists($pattern->getPattern())
                ? [$pattern->getPattern()]
                : []
            );

        foreach ($possibilities as $possibility) {
            if ($pattern->match($possibility)) {
                $class = new \ReflectionClass($possibility);
                if (!$class->isAnonymous()) {
                    $codeExporter->addClass($class);
                }
            }
        }
    }

    private function applyToConstants(CodeExporter $codeExporter, PatternInterface $pattern): void
    {
        $possibilities = $pattern->isWildcard()
            ? \array_keys(\get_defined_constants())
            : (\defined($pattern->getPattern()) ? [$pattern->getPattern()] : []);

        foreach ($possibilities as $possibility) {
            if ($pattern->match($possibility)) {
                $codeExporter->addConstants($possibility);
            }
        }
    }

    private function applyToExtensions(CodeExporter $codeExporter, PatternInterface $pattern): void
    {
        $possibilities = $pattern->isWildcard()
            ? \get_loaded_extensions()
            : (\extension_loaded($pattern->getPattern()) ? [$pattern->getPattern()] : []);

        foreach ($possibilities as $possibility) {
            if ($pattern->match($possibility)) {
                $codeExporter->addExtension(new \ReflectionExtension($possibility));
            }
        }
    }
}
