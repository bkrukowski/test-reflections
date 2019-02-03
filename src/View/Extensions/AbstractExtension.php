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

use Twig\Extension\AbstractExtension as BaseExtension;
use Twig\TwigFilter;

/**
 * @internal
 */
abstract class AbstractExtension extends BaseExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $result = [];
        foreach ($this->getFilterMapping() as $filterName => $functionName) {
            $result[] = new TwigFilter($filterName, \Closure::fromCallable([$this, $functionName]));
        }

        return $result;
    }

    /**
     * Hashmap of twigFilter => classMethod.
     *
     * @return string[]
     */
    abstract protected function getFilterMapping(): array;
}
