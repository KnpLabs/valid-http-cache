<?php

namespace Valid\Rule;

use Symfony\Component\HttpFoundation\Request;

interface ETag extends Rule
{
    function getEtag(Request $request);
}
