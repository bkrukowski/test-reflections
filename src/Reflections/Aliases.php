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

namespace PhpHeaderFile\Reflections;

final class Aliases
{
    /**
     * @param string $class
     *
     * @return string[]
     */
    public static function getAliases(string $class): array
    {
        if (!\class_exists($class) && !\interface_exists($class) && !\trait_exists($class)) {
            throw new \InvalidArgumentException(\sprintf('Class/interface/trait `%s` does not exist', $class));
        }

        return self::prepareMapping()[self::normalizeClassName($class)];
    }

    private static function normalizeClassName(string $class): string
    {
        $class = \ltrim($class, '\\');
        $reflection = new \ReflectionClass($class);

        if (
            (0 === \strcasecmp($class, $reflection->getName()))
            && ($reflection->getName() !== $class)
        ) {
            $class = $reflection->getName();
        }

        return $class;
    }

    private static function prepareMapping(): array
    {
        $names = self::getNames();
        $result = [];

        foreach ($names as $name) {
            $result[$name] = [];
        }

        foreach ($names as $name) {
            $reflection = new \ReflectionClass($name);
            if (0 !== \strcasecmp($reflection->getName(), $name)) {
                $result[$reflection->getName()][] = $name;
            }
        }

        return $result;
    }

    private static function getNames(): array
    {
        return \array_merge(
            \get_declared_classes(),
            \get_declared_interfaces(),
            \get_declared_traits()
        );
    }
}
