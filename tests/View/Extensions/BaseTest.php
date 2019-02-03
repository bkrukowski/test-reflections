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

use PhpHeaderFile\BaseTest as ParentTest;
use Twig\TwigFilter;

/**
 * @internal
 */
abstract class BaseTest extends ParentTest
{
    public function testGetFilters()
    {
        $extension = $this->createExtension();

        $names = \array_map(
            function (TwigFilter $filter) {
                return $filter->getName();
            },
            $extension->getFilters()
        );
        \sort($names);

        $expected = $this->getExpectedFilters();
        \sort($expected);

        $this->assertSame($expected, $names);
    }

    /**
     * @param string $method
     * @param array  ...$parameters
     *
     * @return mixed
     */
    protected function callMethod(string $method, ...$parameters)
    {
        $reflectionMethod = new \ReflectionMethod(
            $extension = $this->createExtension(),
            $method
        );
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($extension, ...$parameters);
    }

    /**
     * @return string[]
     */
    abstract protected function getExpectedFilters(): array;

    abstract protected function createExtension(): AbstractExtension;
}
