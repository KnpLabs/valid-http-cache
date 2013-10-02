<?php

namespace Valid\Rule;

use Symfony\Component\HttpFoundation\Request;

interface LastModified extends Rule
{
    function getLastModified(Request $request);
}
