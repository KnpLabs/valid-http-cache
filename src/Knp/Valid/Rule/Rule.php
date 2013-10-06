<?php

namespace Knp\Valid\Rule;

use Symfony\Component\HttpFoundation\Request;

interface Rule
{
    function supports(Request $request);
}
