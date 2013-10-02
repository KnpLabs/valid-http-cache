<?php

namespace Valid\Stack;

use Valid\ResponseManipulator;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $response = $this->app->handle($request, $type, $catch);
        $this->responseManipulator->handle($request, $response);

        return $response;
    }
}
