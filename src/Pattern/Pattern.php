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

final class Pattern implements PatternInterface
{
    public const APPLY_TO_FUNCTIONS = 0b0001;
    public const APPLY_TO_CLASSES = 0b0010;
    public const APPLY_TO_EXTENSIONS = 0b0100;
    public const APPLY_TO_CONSTANTS = 0b1000;
    public const APPLY_TO_ALL = 0b1111;

    /**
     * @var int
     */
    private $appliesTo;

    /**
     * @var bool
     */
    private $isRegex;

    /**
     * @var string
     */
    private $pattern;

    public function __construct(int $appliesTo, bool $isRegex, string $pattern)
    {
        $this->appliesTo = $appliesTo;
        $this->isRegex = $isRegex;
        $this->pattern = $pattern;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function appliesToFunctions(): bool
    {
        return $this->appliesTo(static::APPLY_TO_FUNCTIONS);
    }

    public function appliesToClasses(): bool
    {
        return $this->appliesTo(static::APPLY_TO_CLASSES);
    }

    public function appliesToExtensions(): bool
    {
        return $this->appliesTo(static::APPLY_TO_EXTENSIONS);
    }

    public function appliesToConstants(): bool
    {
        return $this->appliesTo(static::APPLY_TO_CONSTANTS);
    }

    public function isWildcard(): bool
    {
        return $this->isRegex;
    }

    public function match(string $name): bool
    {
        return $this->isRegex
            ? (bool) \preg_match($this->pattern, $name)
            : \mb_strtolower($this->pattern) === \mb_strtolower(\ltrim($name, '\\'));
    }

    private function appliesTo(int $binaryMask): bool
    {
        return (bool) ($binaryMask & $this->appliesTo);
    }
}
