<?php
declare(strict_types=1);

namespace Inotify;

use Generator;
use InvalidArgumentException;

class InotifyProxy implements InotifyProxyInterface
{
    private $inotify;
    /**
     * @var WatchedResource[]
     */
    private $watchedResources;

    public function __construct()
    {
        $this->inotify = inotify_init();
    }

    /**
     * @return Generator|InotifyEvent[]
     */
    public function read(): Generator
    {
        $events = inotify_read($this->inotify);
        if (false !== $events) {
            foreach ($events as $event) {
                $event = new InotifyEvent(
                    $event['wd'],
                    InotifyEventCodeEnum::createFromMask($event['mask']),
                    $event['cookie'],
                    $event['name'],
                    $this->watchedResources[$event['wd']],
                    time()
                );

                // if file is removed we need clean watchedResources
                if ($event->getInotifyEventCode() === InotifyEventCodeEnum::ON_IGNORED()->getValue()) {
                    unset($this->watchedResources[$event->getId()]);
                }

                yield $event;
            }
        }
        unset($events);
    }

    public function addWatch(WatchedResource $watchedResource): void
    {
        if (!is_readable($watchedResource->getPathname())) {
            throw new InvalidArgumentException('Resource not exists: "' . $watchedResource->getPathname() . '""');
        }

        $id = inotify_add_watch(
            $this->inotify,
            $watchedResource->getPathname(),
            $watchedResource->getWatchOnChangeFlags()
        );

        $this->watchedResources[$id] = $watchedResource;
    }

    public function closeWatchers(): void
    {
        foreach ($this->watchedResources as $id => $resource) {
            inotify_rm_watch($this->inotify, $id);
        }
    }

    public function havePendingEvents(): bool
    {
        return inotify_queue_len($this->inotify) > 1;
    }

    public function __destruct()
    {
        if (is_resource($this->inotify)) {
            fclose($this->inotify);
        }
    }
}