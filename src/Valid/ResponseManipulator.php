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
        foreach ($this->rules as $rule) {
            if ($rule->supports($request)) {
                if ($rule instanceof Rule\ETag) {
                    $response->setEtag($rule->getETag($request));
                }
                if ($rule instanceof Rule\LastModified) {
                    $response->setLastModified($rule->getLastModified($request));
                }
            }
        }

        $response->isNotModified($request);
    }
}
