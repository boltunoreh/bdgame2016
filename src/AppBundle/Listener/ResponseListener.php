<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 08.02.2016
 * Time: 17:34
 */

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $headers = $event->getResponse()->headers;

        $headers->set('X-Frame-Options', 'deny');
        $headers->set('X-Content-Type-Options', 'nosniff');
    }
}