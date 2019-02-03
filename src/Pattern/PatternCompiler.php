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

final class PatternCompiler implements PatternCompilerInterface
{
    private const PREFIX_MAPPING = [
        'fn' => Pattern::APPLY_TO_FUNCTIONS,
        'class' => Pattern::APPLY_TO_CLASSES,
        'ext' => Pattern::APPLY_TO_EXTENSIONS,
        'const' => Pattern::APPLY_TO_CONSTANTS,
    ];

    /**
     * {@inheritdoc}
     */
    public function compile(string $pattern): Pattern
    {
        $pattern = \str_replace('/', '\\', $pattern);

        if (null !== $result = $this->getTypePattern($pattern)) {
            return $result;
        }

        $pattern = \ltrim($pattern, '\\');

        $isRegex = $this->isRegex($pattern);
        $resultPattern = $isRegex
            ? $this->patternToRegex($pattern)
            : $pattern;

        return new Pattern(Pattern::APPLY_TO_ALL, $isRegex, $resultPattern);
    }

    private function getTypePattern(string $pattern): ?Pattern
    {
        $regex = '/^(?<type>'.\implode('|', \array_keys(self::PREFIX_MAPPING)).')-(?<name>.{1,})$/i';
        if (\preg_match($regex, $pattern, $matches)) {
            $isRegex = $this->isRegex($pattern);

            $name = \ltrim($matches['name'], '\\');

            return new Pattern(
                self::PREFIX_MAPPING[$matches['type']],
                $isRegex,
                $isRegex ? $this->patternToRegex($name) : $name
            );
        }

        return null;
    }

    private function isRegex(string $pattern): bool
    {
        return false !== \mb_strpos($pattern, '*');
    }

    private function patternToRegex(string $input): string
    {
        $parts = \array_map(
            function ($part) {
                return \preg_quote($part, '/');
            },
            \explode('*', $input)
        );

        return '/^'.\implode('.*', $parts).'$/i';
    }
}
