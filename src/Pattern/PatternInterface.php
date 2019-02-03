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

interface PatternInterface
{
    public function getPattern(): string;

    public function appliesToFunctions(): bool;

    public function appliesToClasses(): bool;

    public function appliesToExtensions(): bool;

    public function appliesToConstants(): bool;

    public function isWildcard(): bool;

    public function match(string $name): bool;
}
