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
 * @covers \PhpHeaderFile\Pattern\PatternCompiler
 *
 * @internal
 */
final class PatternCompilerTest extends BaseTest
{
    /**
     * @dataProvider providerCompile
     *
     * @param string           $pattern
     * @param PatternInterface $expected
     */
    public function testCompile(string $pattern, PatternInterface $expected)
    {
        $result = (new PatternCompiler())->compile($pattern);

        $this->assertSame($expected->getPattern(), $result->getPattern());
        $this->assertSame($expected->appliesToFunctions(), $result->appliesToFunctions());
        $this->assertSame($expected->appliesToClasses(), $result->appliesToClasses());
        $this->assertSame($expected->appliesToExtensions(), $result->appliesToExtensions());
        $this->assertSame($expected->appliesToConstants(), $result->appliesToConstants());
        $this->assertSame($expected->isWildcard(), $result->isWildcard());
    }

    public function providerCompile(): iterable
    {
        return [
            ['ext-extension', new Pattern(Pattern::APPLY_TO_EXTENSIONS, false, 'extension')],
            ['fn-str*', new Pattern(Pattern::APPLY_TO_FUNCTIONS, true, '/^str.*$/i')],
            ['fn-\str*', new Pattern(Pattern::APPLY_TO_FUNCTIONS, true, '/^str.*$/i')],
            ['fn-mb_strpos', new Pattern(Pattern::APPLY_TO_FUNCTIONS, false, 'mb_strpos')],
            ['fn-\mb_strpos', new Pattern(Pattern::APPLY_TO_FUNCTIONS, false, 'mb_strpos')],
            ['sth-sth*', new Pattern(Pattern::APPLY_TO_ALL, true, '/^sth\-sth.*$/i')],
            ['mb_*', new Pattern(Pattern::APPLY_TO_ALL, true, '/^mb_.*$/i')],
            ['mb_strpos', new Pattern(Pattern::APPLY_TO_ALL, false, 'mb_strpos')],
            ['\mb_strpos', new Pattern(Pattern::APPLY_TO_ALL, false, 'mb_strpos')],
        ];
    }
}
