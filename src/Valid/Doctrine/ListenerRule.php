<?php

namespace Valid\Doctrine;

use Valid\Rule;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Symfony\Component\HttpFoundation\Request;

class ListenerRule implements EventSubscriber, Rule\ETag, Rule\LastModified
{
    private $objects = array();

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postDelete',
            'postFlush',
        );
    }

    public function postPersist(EventArgs $event)
    {
        $this->handle($event->getObject());
    }

    public function postUpdate(EventArgs $event)
    {
        $this->handle($event->getObject());
    }

    public function postDelete(EventArgs $event)
    {
        $this->handle($event->getObject());
    }

    private function handle($object)
    {
        if ($object instanceof Rule\Rule) {
            $this->objects[] = $object;
        }
    }

    public function supports(Request $request)
    {
        foreach ($this->objects as $object) {
            if ($object->supports($request)) {
                return true;
            }
        }

        return false;
    }

    public function getLastModified(Request $request)
    {
        foreach ($this->objects as $object) {
            if ($object instanceof Rule\LastModified) {
                return $object->getLastModified($request);
            }
        }
    }

    public function getETag(Request $request)
    {
        foreach ($this->objects as $object) {
            if ($object instanceof Rule\ETag) {
                return $object->getETag($request);
            }
        }
    }

    public function postFlush(EventArgs $event)
    {
        $this->objects = array();
    }
}
