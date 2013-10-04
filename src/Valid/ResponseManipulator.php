<?php

namespace Valid;

use Valid\Rule;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseManipulator
{
    private $rules;

    public function __construct(array $rules = array())
    {
        $this->rules = $rules;
    }

    public function addRule(Rule\Rule $rule)
    {
        $this->rules[] = $rule;
    }

    public function handle(Request $request, Response $response)
    {
        // TODO so much if!
        $isNotModified = false;
        foreach ($this->rules as $rule) {
            if ($rule->supports($request)) {
                if ($rule instanceof Rule\ETag) {
                    if ($etag = $rule->getETag($request)) {
                        $response->setEtag($etag);
                    }
                }
                if ($rule instanceof Rule\LastModified) {
                    if ($lastModified = $rule->getLastModified($request)) {
                        $response->setLastModified($lastModified);
                    }
                }

                $isNotModified = $response->isNotModified($request);
            }
        }

        return $isNotModified;
    }
}
