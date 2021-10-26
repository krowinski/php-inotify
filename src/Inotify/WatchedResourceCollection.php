<?php

declare(strict_types=1);

namespace Inotify;

use Vistik\Collections\TypedCollection;

class WatchedResourceCollection extends TypedCollection
{
    protected $type = WatchedResource::class;

    public static function createSingle(
        string $pathname,
        int $watchOnChangeFlags,
        string $customName
    ): self {
        return (new self())->push(new WatchedResource($pathname, $watchOnChangeFlags, $customName));
    }
}