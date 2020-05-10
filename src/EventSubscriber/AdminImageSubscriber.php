<?php

namespace App\EventSubscriber;


use App\Service\MediaService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class AdminImageSubscriber implements EventSubscriberInterface
{
    private $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }
    public function onEasyAdminPrePersist(GenericEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            'easy_admin.pre_persist' => 'onEasyAdminPrePersist',
        ];
    }
}
