# Php-header-file

Generate header file of extensions for your IDE.

## Why?

For supporting syntax autocomplete of your IDE by custom extensions like `swoole` or `uopz`.

## Example

`./php-header-file export ext-pdo`

```php
(...)
namespace
{
    class PDO
    {
        public const ATTR_AUTOCOMMIT = 0;
        public const ATTR_CASE = 8;
        public const ATTR_CLIENT_VERSION = 5;
        public const ATTR_CONNECTION_STATUS = 7;
        public const ATTR_CURSOR = 10;
        public const ATTR_CURSOR_NAME = 9;
        public const ATTR_DEFAULT_FETCH_MODE = 19;
        public const ATTR_DRIVER_NAME = 16;
        public const ATTR_EMULATE_PREPARES = 20;
        public const ATTR_ERRMODE = 3;
        public const ATTR_FETCH_CATALOG_NAMES = 15;
        public const ATTR_FETCH_TABLE_NAMES = 14;
        public const ATTR_MAX_COLUMN_LEN = 18;
        public const ATTR_ORACLE_NULLS = 11;
        public const ATTR_PERSISTENT = 12;
(...)
        public static function getAvailableDrivers() {}
        public function __construct($dsn, $username, $passwd, $options) {}
        final public function __sleep() {}
(...)
}
(...)
```

## Help

```
Description:
  Exports definitions of functions/classes/constants/extensions

Usage:
  export [options] [--] <patterns>...

Arguments:
  patterns               Things to be exported e.g. "ext-pdo", available prefixes: fn-, class-, ext-, const-

Options:
  -o, --output[=OUTPUT]  Name of file where php code will be written [default: "headers.php"]
  -f, --force            Overwrite if file exists
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
