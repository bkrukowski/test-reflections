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

use PhpHeaderFile\Fixtures\GeneralClasses\ClassWithAlias;
use PhpHeaderFile\Fixtures\ReflectionExtension\Base;
use PhpHeaderFile\Fixtures\ReflectionExtension\DecoratedSomethingElseInterface;
use PhpHeaderFile\Fixtures\ReflectionExtension\Something;
use PhpHeaderFile\Fixtures\ReflectionExtension\SomethingElse;
use PhpHeaderFile\Fixtures\ReflectionExtension\SomethingElseInterface;
use PhpHeaderFile\Fixtures\ReflectionExtension\SomethingInterface;

/**
 * @covers \PhpHeaderFile\View\Extensions\AbstractExtension
 * @covers \PhpHeaderFile\View\Extensions\ReflectionExtension
 *
 * @internal
 */
final class ReflectionExtensionTest extends BaseTest
{
    /**
     * @dataProvider providerModifierNames
     *
     * @param int      $modifiers
     * @param string[] $expected
     */
    public function testModifierNames(int $modifiers, array $expected): void
    {
        $names = $this->callMethod('modifierNames', $modifiers);
        \sort($names);
        \sort($expected);

        $this->assertSame($expected, $names);
    }

    public function providerModifierNames(): iterable
    {
        return [
            [0, ['public']],
            [\ReflectionProperty::IS_PUBLIC, ['public']],
            [\ReflectionProperty::IS_PRIVATE, ['private']],
            [\ReflectionMethod::IS_PUBLIC, ['public']],
            [\ReflectionMethod::IS_FINAL | \ReflectionMethod::IS_STATIC, ['final', 'static']],
        ];
    }

    /**
     * @dataProvider providerKeywordAliases
     *
     * @param string $className
     * @param array  $expected
     */
    public function testKeywordAliases(string $className, array $expected): void
    {
        $aliases = $this->callMethod('keywordAliases', $className);
        \sort($aliases);
        \sort($expected);
        $this->assertSame($expected, $aliases);
    }

    public function providerKeywordAliases(): iterable
    {
        return [
            [ClassWithAlias::class, ['phpheaderfile\fixtures\generalclasses\class_with_alias']],
            [static::class, []],
        ];
    }

    /**
     * @dataProvider providerGetInterfacesNames
     *
     * @param string   $class
     * @param string[] $expected
     */
    public function testGetInterfacesNames(string $class, array $expected)
    {
        $result = $this->callMethod('getInterfacesNames', new \ReflectionClass($class));
        \sort($result);
        \sort($expected);
        $this->assertSame($expected, $result);
    }

    public function providerGetInterfacesNames(): iterable
    {
        return [
            [Base::class, [SomethingInterface::class]],
            [Something::class, []],
            [SomethingElse::class, [SomethingElseInterface::class]],
            [DecoratedSomethingElseInterface::class, [SomethingElseInterface::class]],
        ];
    }

    protected function getExpectedFilters(): array
    {
        return [
            'modifierNames',
            'keywordAliases',
            'getInterfacesNames',
            'declaredIn',
        ];
    }

    protected function createExtension(): AbstractExtension
    {
        return new ReflectionExtension();
    }
}
