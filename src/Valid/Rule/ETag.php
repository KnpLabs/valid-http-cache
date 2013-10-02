<?php

namespace Valid\Rule;

use Symfony\Component\HttpFoundation\Request;

interface ETag
{
    function supports(Request $request);
    function getEtag(Request $request);
}
