<?php

declare(strict_types=1);

namespace Inotify;

class WatchedResource
{
    private string $pathname;
    private int $watchOnChangeFlags;
    private string $customName;

    public function __construct(
        string $pathname,
        int $watchOnChangeFlags,
        string $customName
    ) {
        $this->pathname = $pathname;
        $this->watchOnChangeFlags = $watchOnChangeFlags;
        $this->customName = $customName;
    }

    public function getWatchOnChangeFlags(): int
    {
        return $this->watchOnChangeFlags;
    }

    public function getCustomName(): string
    {
        return $this->customName;
    }

    public function getPathname(): string
    {
        return $this->pathname;
    }
}