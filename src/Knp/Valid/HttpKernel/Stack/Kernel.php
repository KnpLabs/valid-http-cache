<?php

namespace Knp\Valid\Stack;

use Knp\Valid\ResponseManipulator;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel implements HttpKernelInterface
{
    private $kernel;
    private $responseManipulator;

    public function __construct(HttpKernelInterface $kernel, ResponseManipulator $responseManipulator)
    {
        $this->kernel = $kernel;
        $this->responseManipulator = $responseManipulator;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $response = new Response;
        if ($this->responseManipulator->handle($request, $response)) {
            return $response;
        }
        $response = $this->kernel->handle($request, $type, $catch);
        $this->responseManipulator->handle($request, $response);

        return $response;
    }
}
