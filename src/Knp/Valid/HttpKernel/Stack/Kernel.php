<?php

namespace Knp\Valid\Stack;

use Knp\Valid\ResponseManipulator;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel implements HttpKernelInterface
{
    private $app;
    private $responseManipulator;

    public function __construct(HttpKernelInterface $app, ResponseManipulator $responseManipulator)
    {
        $this->app = $app;
        $this->responseManipulator = $responseManipulator;
    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $response = new Response;
        if ($this->responseManipulator->handle($request, $response)) {
            return $response;
        }
        $response = $this->app->handle($request, $type, $catch);

        return $response;
    }
}
