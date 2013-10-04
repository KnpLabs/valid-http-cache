<?php

namespace Valid\HttpKernel;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Response;

class EventListener implements EventSubscriberInterface
{
    private $resolver;

    public function __construct(ControllerResolverInterface $resolver = null)
    {
        $this->resolver = $resolver ?: new ControllerResolver;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE   => 'onKernelResponse',
        );
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $response = new Response;
        $controller = $event->getController();
        $event->setController(function() use($request, $response, $controller) {
            if ($this->responseManipulator->handle($request, $response)) {
                return $response;
            }

            $arguments = $this->resolver->getArguments($request, $controller);
            return call_user_func_array($controller, $arguments);
        });
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->responseManipulator->handle($event->getRequest(), $event->getResponse());
    }
}
