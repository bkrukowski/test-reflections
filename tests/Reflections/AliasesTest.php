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

use PhpHeaderFile\BaseTest;
use PhpHeaderFile\Fixtures\GeneralClasses\ClassWithAlias;

/**
 * @covers \PhpHeaderFile\Reflections\Aliases
 *
 * @internal
 */
final class AliasesTest extends BaseTest
{
    public function testAll()
    {
        $this->assertSame([], Aliases::getAliases(static::class));
        $this->assertSame(
            ['phpheaderfile\fixtures\generalclasses\class_with_alias'],
            Aliases::getAliases(ClassWithAlias::class)
        );
        $this->assertSame(
            ['phpheaderfile\fixtures\generalclasses\class_with_alias'],
            Aliases::getAliases(\mb_strtolower(ClassWithAlias::class))
        );
        $this->assertSame(
            [],
            Aliases::getAliases('phpheaderfile\fixtures\generalclasses\class_with_alias')
        );
    }

    public function testInvalidClassName()
    {
        $this->expectException(\InvalidArgumentException::class);
        Aliases::getAliases('@@@');
    }
}
