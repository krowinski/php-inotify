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
     * @param WatchedResourceCollection|WatchedResource[] $collection
     */
    public function consume(WatchedResourceCollection $collection): void
    {
        foreach ($collection as $resource) {
            $this->inotifyProxy->addWatch($resource);
        }

        while ($events = $this->inotifyProxy->read()) {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }

        $this->inotifyProxy->closeWatchers();
    }

    public function registerSubscriber(EventSubscriberInterface $eventSubscribers): self
    {
        $this->eventDispatcher->addSubscriber($eventSubscribers);

        return $this;
    }
}