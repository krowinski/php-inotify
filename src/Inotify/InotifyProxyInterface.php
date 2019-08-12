<?php
declare(strict_types=1);

namespace Inotify;

use Generator;

interface InotifyProxyInterface
{
    /**
     * @return Generator|InotifyEvent[]
     */
    public function read(): Generator;

    public function addWatch(WatchedResource $watchedResource): void;

    public function closeWatchers(): void;

    public function havePendingEvents(): bool;
}