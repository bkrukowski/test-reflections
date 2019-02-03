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

use PhpHeaderFile\Reflections\Aliases;

/**
 * @internal
 */
final class ReflectionExtension extends AbstractExtension
{
    protected function getFilterMapping(): array
    {
        return [
            'modifierNames' => 'modifierNames',
            'keywordAliases' => 'keywordAliases',
            'getInterfacesNames' => 'getInterfacesNames',
            'declaredIn' => 'declaredIn',
        ];
    }

    protected function modifierNames(int $modifiers): array
    {
        if ([] === $result = \Reflection::getModifierNames($modifiers)) {
            $result = ['public'];
        }

        return $result;
    }

    protected function keywordAliases(string $className): array
    {
        return Aliases::getAliases($className);
    }

    protected function getInterfacesNames(\ReflectionClass $class): array
    {
        $parentClassesInterfaces = [];
        $parent = $class;
        while (false !== $parent = $parent->getParentClass()) {
            $parentClassesInterfaces = \array_merge($parent->getInterfaceNames());
        }

        /** @var \ReflectionClass[] $result */
        $result = [];
        foreach ($class->getInterfaces() as $interface) {
            if (!\in_array($interface->getName(), $parentClassesInterfaces, true)) {
                $result[$interface->getName()] = $interface;
            }
        }

        foreach ($result as $interface => $reflection) {
            foreach ($result as $potentialParent => $parentReflection) {
                if ($reflection->isSubclassOf($potentialParent)) {
                    unset($result[$interface]);

                    break;
                }
            }
        }

        return \array_values(\array_keys($result));
    }

    /**
     * @param \ReflectionClassConstant[]|\ReflectionMethod[]|\ReflectionProperty[] $reflections
     * @param string                                                               $className
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionClassConstant[]|\ReflectionMethod[]|\ReflectionProperty[]
     */
    protected function declaredIn(array $reflections, string $className): array
    {
        $class = new \ReflectionClass($className);

        $result = [];
        foreach ($reflections as $reflection) {
            if ($reflection->getDeclaringClass()->getName() === $class->getName()) {
                $result[] = $reflection;
            }
        }

        $visibilityComparator = function ($a, $b): int {
            /** @var \ReflectionClassConstant|\ReflectionMethod|\ReflectionProperty $a */
            /** @var \ReflectionClassConstant|\ReflectionMethod|\ReflectionProperty $b */
            $left = $a->isPublic() ? -1 : ($a->isProtected() ? 0 : 1);
            $right = $b->isPublic() ? -1 : ($b->isProtected() ? 0 : 1);

            if ($a instanceof \ReflectionMethod) {
                $left -= $a->isStatic() ? 5 : 0;
                $right -= $b->isStatic() ? 5 : 0;

                $left += $a->isAbstract() ? .1 : 0;
                $right += $b->isAbstract() ? .1 : 0;
            }

            return $left <=> $right;
        };

        \usort(
            $result,
            function ($a, $b) use ($visibilityComparator) {
                if (0 !== $result = $visibilityComparator($a, $b)) {
                    return $result;
                }

                // @var \ReflectionMethod|\ReflectionProperty|\ReflectionClassConstant $a
                // @var \ReflectionMethod|\ReflectionProperty|\ReflectionClassConstant $b
                return \strcmp($a->getName(), $b->getName());
            }
        );

        return $result;
    }
}
