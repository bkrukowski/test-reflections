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

namespace PhpHeaderFile;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->expectOutputString('');
    }

    protected static function requireXdebug(): void
    {
        if (!\extension_loaded('xdebug')) {
            static::markTestSkipped('Xdebug required');
        }
    }
}
