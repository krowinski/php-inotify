<?php
declare(strict_types=1);

namespace Inotify;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InotifyConsumerFactory
{
    private $inotifyProxy;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        InotifyProxyInterface $inotifyProxy = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->inotifyProxy = $inotifyProxy;
        $this->eventDispatcher = $eventDispatcher;

        if (null === $eventDispatcher) {
            $this->eventDispatcher = new EventDispatcher();
        }
        if (null === $inotifyProxy) {
            $this->inotifyProxy = new InotifyProxy();
        }
    }

    /**
     * @param WatchedDirCollection|WatchedDir[] $watchedDirCollection
     */
    public function consume(WatchedDirCollection $watchedDirCollection): void
    {
        foreach ($watchedDirCollection as $watchedDir) {
            $this->inotifyProxy->addWatch($watchedDir);
        }

        while ($events = $this->inotifyProxy->read()) {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }

        $this->inotifyProxy->closeWatchers();
    }

    public function registerSubscriber(EventSubscriberInterface $eventSubscribers): void
    {
        $this->eventDispatcher->addSubscriber($eventSubscribers);
    }
}