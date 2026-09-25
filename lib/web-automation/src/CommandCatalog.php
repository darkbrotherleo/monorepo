<?php
declare(strict_types=1);

namespace Monorepo\WebAutomation;

/** Port of openbrowser CommandSchema/Executor catalog — PHP-native */
class CommandCatalog
{
    public const COMMANDS = [
        'click' => ['selector' => 'string'],
        'type' => ['selector' => 'string', 'text' => 'string'],
        'navigate' => ['url' => 'string'],
        'scroll' => ['direction' => 'up|down', 'amount?' => 'int'],
        'extract' => ['selector?' => 'string', 'format?' => 'markdown|html|text'],
        'waitFor' => ['selector' => 'string', 'timeout?' => 'int'],
    ];

    public static function describe(): string
    {
        return implode("\n", array_map(fn($k,$v) => "$k: ".json_encode($v), array_keys(self::COMMANDS), self::COMMANDS));
    }
}
