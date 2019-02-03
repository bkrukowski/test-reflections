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

/**
 * @internal
 */
final class SplExtension extends AbstractExtension
{
    protected function getFilterMapping(): array
    {
        return [
            'escapeType' => 'escapeType',
            'varExport' => 'varExport',
            'except' => 'except',
        ];
    }

    protected function varExport($var): string
    {
        $mapping = [
            [null, 'null'],
            [[], 'array()'],
        ];
        foreach ($mapping as [$value, $text]) {
            if ($var === $value) {
                return $text;
            }
        }

        return \rtrim(\var_export($var, true), "\n");
    }

    protected function except(iterable $array, array $excepts): array
    {
        /**
         * For \ReflectionFunctionAbstract::$name.
         */
        $array = \is_object($array)
            ? \iterator_to_array($array, false)
            : $array;

        return \array_filter(
            $array,
            function ($element) use ($excepts) {
                return !\in_array($element, $excepts, true);
            }
        );
    }

    protected function escapeType(string $type): string
    {
        if ((\class_exists($type) || \interface_exists($type) || \trait_exists($type)) && '\\' !== \mb_substr($type, 0, 1)) {
            $type = '\\'.$type;
        }

        return $type;
    }
}
