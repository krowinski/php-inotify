<?php


use Inotify\InotifyConsumerFactory;
use Inotify\InotifyEvent;
use Inotify\InotifyEventCodeEnum;
use Inotify\WatchedResourceCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

include __DIR__ . '/../vendor/autoload.php';

$inotifyConsumerFactory = new InotifyConsumerFactory();
$inotifyConsumerFactory->registerSubscriber(
    new class implements EventSubscriberInterface
    {
        public static function getSubscribedEvents(): array
        {
            return [InotifyEvent::class => 'onInotifyEvent'];
        }

        public function onInotifyEvent(InotifyEvent $event): void
        {
            echo $event;
        }
    }
);
$inotifyConsumerFactory->consume(
    WatchedResourceCollection::createSingle(
        sys_get_temp_dir(),
        // sys_get_temp_dir() . '/test.log',
        //InotifyEventCodeEnum::ON_CREATE()->getValue() | InotifyEventCodeEnum::ON_DELETE()->getValue(),
        InotifyEventCodeEnum::ON_ALL_EVENTS()->getValue(),
        'test'
    )
);

