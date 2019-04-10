# Php-header-file

Generate header file of extensions for your IDE.

## Usage

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
