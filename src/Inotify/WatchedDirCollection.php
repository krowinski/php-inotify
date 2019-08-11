<?php
declare(strict_types=1);

namespace Inotify;

use Vistik\Collections\TypedCollection;

class WatchedDirCollection extends TypedCollection
{
    protected $type = WatchedDir::class;

    public static function createSingle(
        string $pathname,
        int $watchOnChangeFlags,
        string $customName
    ): self {
        return (new self())->push(new WatchedDir($pathname, $watchOnChangeFlags, $customName));
    }
}