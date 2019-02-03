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

use Twig\Environment as TwigEnv;

final class CodeExporter implements ExporterInterface
{
    /**
     * @var TwigEnv
     */
    private $twig;

    /**
     * @var \ReflectionExtension[]
     */
    private $extensions = [];

    /**
     * @var \ReflectionClass[]
     */
    private $classes = [];

    /**
     * @var \ReflectionFunction
     */
    private $functions = [];

    /**
     * @var array
     */
    private $constants = [];

    public function __construct(TwigEnv $twig)
    {
        $this->twig = $twig;
    }

    public function addExtension(\ReflectionExtension $extension): void
    {
        $this->extensions[$extension->getName()] = $extension;
    }

    public function addClass(\ReflectionClass $class): void
    {
        $this->classes[$class->getName()] = $class;
    }

    public function addFunction(\ReflectionFunction $function): void
    {
        $this->functions[$function->getName()] = $function;
    }

    public function addConstants(string $constantName): void
    {
        $this->constants[$constantName] = $constantName;
    }

    public function export(): string
    {
        return $this->twig->render('code.twig', [
            'classes' => $this->getClasses(),
            'functions' => $this->getFunctions(),
            'constants' => $this->getConstants(),
        ]);
    }

    public function hasThingsToExport(): bool
    {
        return \count($this->getClasses()) > 0
            || \count($this->getFunctions()) > 0
            || \count($this->getConstants()) > 0;
    }

    public function listExportedThings(): iterable
    {
        yield from \array_values(\array_map(
            function (\ReflectionExtension $extension) {
                return 'ext-'.$extension->getName();
            },
            $this->extensions
        ));

        yield from \array_values(\array_map(
            function (\ReflectionClass $class) {
                return 'class-'.$class->getName();
            },
            $this->getClasses()
        ));

        yield from \array_values(\array_map(
            function (\ReflectionFunctionAbstract $function) {
                return 'fn-'.$function->getName();
            },
            $this->getFunctions()
        ));

        yield from \array_values(\array_map(
            function (array $parts) {
                [$namespace, $const] = $parts;

                return 'const-'.('' !== $namespace ? $namespace.'\\' : '').$const;
            },
            $this->getConstants()
        ));
    }

    /**
     * @param \ReflectionClass|\ReflectionFunction $a
     * @param \ReflectionClass|\ReflectionFunction $b
     *
     * @return int
     */
    private function namespaceCmp($a, $b): int
    {
        return \strcasecmp($a->getNamespaceName(), $b->getNamespaceName()) ?: \strcasecmp($a->getName(), $b->getName());
    }

    /**
     * @return \ReflectionClass[]
     */
    private function getClasses(): array
    {
        $classes = $this->classes;
        foreach ($this->extensions as $extension) {
            foreach ($extension->getClasses() as $class) {
                $classes[$class->getName()] = $class;
            }
        }

        \usort($classes, [$this, 'namespaceCmp']);

        return $classes;
    }

    /**
     * @return \ReflectionFunction[]
     */
    private function getFunctions(): array
    {
        $functions = $this->functions;
        foreach ($this->extensions as $extension) {
            foreach ($extension->getFunctions() as $function) {
                $functions[$function->getName()] = $function;
            }
        }

        \usort($functions, [$this, 'namespaceCmp']);

        return $functions;
    }

    private function getConstants(): array
    {
        $constants = $this->constants;
        foreach ($this->extensions as $extension) {
            foreach ($extension->getConstants() as $name => $value) {
                $constants[$name] = $name;
            }
        }

        $splitNames = function (string $constName) {
            $names = \explode('\\', $constName);
            $name = \array_pop($names);

            return [\implode('\\', $names), $name];
        };

        $result = [];
        foreach ($constants as $const) {
            $result[] = $splitNames($const);
        }

        \usort($result, function ($a, $b) {
            return \strcasecmp($a[0], $b[0]) ?: \strcasecmp($a[1], $b[1]);
        });

        return $result;
    }
}
