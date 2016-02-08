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

        $event
            ->getResponse()
            ->headers
            ->replace([
                'X-Frame-Options' => 'deny',
                'X-Content-Type-Options' => 'nosniff'
            ]);
    }
}