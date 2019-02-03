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

use PhpHeaderFile\View\Extensions\ReflectionExtension;
use PhpHeaderFile\View\Extensions\SplExtension;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Loader\FilesystemLoader;

/**
 * @internal
 */
final class TwigFactory
{
    private const TWIG_CONFIG = [
        'strict_variables' => true,
        'autoescape' => false,
    ];

    public function create()
    {
        $loader = new FilesystemLoader(\implode(\DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'templates']));
        $twig = new Environment($loader, self::TWIG_CONFIG);
        $this->decorate($twig);

        return $twig;
    }

    private function decorate(Environment $twig): void
    {
        foreach ($this->getExtensions() as $extension) {
            $twig->addExtension($extension);
        }
    }

    /**
     * @return AbstractExtension[]
     */
    private function getExtensions(): array
    {
        return [
            new ReflectionExtension(),
            new SplExtension(),
        ];
    }
}
