<?php

namespace spec\Knp\Valid;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResponseManipulatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Valid\ResponseManipulator');
    }
}
