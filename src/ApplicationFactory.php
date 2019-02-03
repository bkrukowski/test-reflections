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

use PhpHeaderFile\Commands\ExportCommand;
use Symfony\Component\Console\Application;

final class ApplicationFactory
{
    private const APP_NAME = 'PHP Header File';

    private const APP_VERSION = '1.0.0';

    public function createApp(): Application
    {
        $application = new Application(self::APP_NAME, self::APP_VERSION);
        $application->add(new ExportCommand());

        return $application;
    }
}
