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

namespace PhpHeaderFile\Fixtures
{
    abstract class AbstractClass
    {
        abstract public function abstractFunction();
    }

    abstract class ChildAbstractClass
    {
    }

    final class FinalClass
    {
    }

    interface IterableInterface
    {
        public function getIterable(): iterable;
    }
}

namespace PhpHeaderFile\Fixtures\Stringable
{
    interface StringableInterface
    {
        public function __toString();
    }
}

namespace PhpHeaderFile\Fixtures\Exportable
{
    trait ExportableTrait
    {
        public function export()
        {
        }
    }
}

namespace PhpHeaderFile\Fixtures\Iterables
{
    use PhpHeaderFile\Fixtures\Exportable\ExportableTrait;
    use PhpHeaderFile\Fixtures\IterableInterface;
    use PhpHeaderFile\Fixtures\Stringable\StringableInterface;

    abstract class IterableClass implements IterableInterface
    {
    }

    trait CountableIterable
    {
        public function count()
        {
        }
    }

    final class IterableArray extends IterableClass implements \Countable, \IteratorAggregate, StringableInterface
    {
        use CountableIterable;
        use ExportableTrait {
            export as __toString;
        }

        public function getIterable(): iterable
        {
        }

        public function getIterator()
        {
        }
    }
}

namespace PhpHeaderFile\Fixtures\Math
{
    interface NumberFormatter
    {
        public function format(float $number): string;
    }

    class Calculator
    {
        public function add(int $a, int $b): int
        {
        }

        public function divide(int $a, int $b): float
        {
        }

        public function getNumberFormatter(): ?NumberFormatter
        {
        }

        public function setNumberFormatter(?NumberFormatter $numberFormatter): void
        {
        }
    }
}

namespace PhpHeaderFile\Fixtures\GeneralClasses
{
    class ClassWithoutModifierNames
    {
        public function hello()
        {
        }
    }

    class ClassWithConstants
    {
        public const FOO = 'foo';
        public const EMPTY_ARRAY = [];
        private const PRIVATE_CONST = 'private const';
    }

    class ClassWithMethods
    {
        public function bbb()
        {
        }

        private function aaa()
        {
        }
    }

    class ClassWithProperties
    {
        public $firstProperty;
        public $secondProperty;
    }

    class ClassWithAlias
    {
    }
    \class_alias(ClassWithAlias::class, __NAMESPACE__.'\\class_with_alias');

    trait SomeTrait
    {
        protected $someProperty;

        public function doSomething()
        {
        }
    }

    class ClassWithTrait
    {
        use SomeTrait;
        protected const SOME_PROTECTED_CONST = 'some protected const';
    }

    abstract class MethodsOrder
    {
        public function publicFn()
        {
        }

        public static function publicStaticFn()
        {
        }

        protected function protectedFn()
        {
        }

        protected function secondProtectedFn()
        {
        }

        protected static function protectedStaticFn()
        {
        }

        abstract protected function abstractProtectedFn();

        private function privateFn()
        {
        }

        private static function privateStaticFn()
        {
        }
    }
}

namespace PhpHeaderFile\Fixtures\ReflectionExtension
{
    interface SomethingInterface
    {
    }

    interface SomethingElseInterface extends SomethingInterface
    {
    }

    interface DecoratedSomethingElseInterface extends SomethingElseInterface
    {
    }

    abstract class Base implements SomethingInterface
    {
        public function doBase()
        {
        }
    }

    class Something extends Base
    {
        public function doSomething()
        {
        }
    }

    class SomethingElse implements SomethingElseInterface
    {
    }
}
