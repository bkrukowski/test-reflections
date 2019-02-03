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

namespace PhpHeaderFile\Pattern;

use PhpHeaderFile\BaseTest;

/**
 * @covers \PhpHeaderFile\Pattern\Pattern
 *
 * @internal
 */
final class PatternTest extends BaseTest
{
    /**
     * @dataProvider providerPattern
     *
     * @param string $patternStr
     * @param bool   $isRegex
     * @param string $name
     * @param bool   $match
     */
    public function testPattern(string $patternStr, bool $isRegex, string $name, bool $match): void
    {
        $pattern = (new Pattern(Pattern::APPLY_TO_ALL, $isRegex, $patternStr));
        $this->assertSame(
            $match,
            $pattern->match($name)
        );
        $this->assertSame($isRegex, $pattern->isWildcard());
        $this->assertSame($patternStr, $pattern->getPattern());
    }

    public function providerPattern(): iterable
    {
        return [
            ['strpos', false, 'strpos', true],
            ['strpos', false, '\strpos', true],
            ['strpos', false, 'stripos', false],
            ['/^mb_.*$/i', true, 'mb_substr', true],
            ['/^mb_.*$/i', true, 'substr', false],
        ];
    }

    /**
     * @dataProvider providerApplierTo
     *
     * @param bool $fn
     * @param bool $class
     * @param bool $ext
     * @param bool $const
     * @param int  $appliesTo
     */
    public function testAppliesTo(bool $fn, bool $class, bool $ext, bool $const, int $appliesTo): void
    {
        $pattern = new Pattern($appliesTo, false, '');
        $this->assertSame($fn, $pattern->appliesToFunctions());
        $this->assertSame($class, $pattern->appliesToClasses());
        $this->assertSame($ext, $pattern->appliesToExtensions());
        $this->assertSame($const, $pattern->appliesToConstants());
    }

    public function providerApplierTo(): iterable
    {
        return [
            [true, false, false, false, Pattern::APPLY_TO_FUNCTIONS],
            [false, true, false, false, Pattern::APPLY_TO_CLASSES],
            [false, false, true, false, Pattern::APPLY_TO_EXTENSIONS],
            [false, false, false, true, Pattern::APPLY_TO_CONSTANTS],
            [true, true, true, true, Pattern::APPLY_TO_ALL],
        ];
    }
}
