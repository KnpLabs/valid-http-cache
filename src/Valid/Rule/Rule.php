<?php

namespace Valid\Rule;

use Symfony\Component\HttpFoundation\Request;

interface Rule
{
    function supports(Request $request);
}
