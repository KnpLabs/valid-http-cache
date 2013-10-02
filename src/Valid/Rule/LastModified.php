<?php

namespace Valid\Rule;

use Symfony\Component\HttpFoundation\Request;

interface LastModified
{
    function supports(Request $request);
    function getLastModified(Request $request);
}
