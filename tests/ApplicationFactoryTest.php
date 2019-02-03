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

use Symfony\Component\Console\Application;

/**
 * @covers \PhpHeaderFile\ApplicationFactory
 *
 * @internal
 */
final class ApplicationFactoryTest extends BaseTest
{
    public function testCreateApp(): void
    {
        $this->assertInstanceOf(Application::class, (new ApplicationFactory())->createApp());
    }
}
