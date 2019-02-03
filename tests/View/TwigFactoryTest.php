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

namespace PhpHeaderFile\View;

use PhpHeaderFile\BaseTest;
use PhpHeaderFile\View\Extensions\ReflectionExtension;
use PhpHeaderFile\View\Extensions\SplExtension;
use Twig\Environment as TwigEnv;

/**
 * @covers \PhpHeaderFile\View\TwigFactory
 *
 * @internal
 */
final class TwigFactoryTest extends BaseTest
{
    public function testCreate(): void
    {
        $twig = (new TwigFactory())->create();
        $this->assertInstanceOf(TwigEnv::class, $twig);

        $extensionClasses = [
            ReflectionExtension::class,
            SplExtension::class,
        ];

        foreach ($extensionClasses as $extensionClass) {
            $this->assertInstanceOf($extensionClass, $twig->getExtension($extensionClass));
        }
    }
}
