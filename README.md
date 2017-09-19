# php-valid

*This project is not maintained anymore*.

## What

A php 5.3+ library to help manage HTTP/1.1 cache validation headers, 
using the symfony/HttpFoundation layer.

## Why

Because there is no known library that provides a clean, decoupled way to manage them.
The actual [docs](http://symfony.com/doc/current/book/http_cache.html#optimizing-your-code-with-validation) just shows dirty controller examples.

## How

By observing requests and asking `Rule` instances if response has changed 
since last time client asked, either using If-Modified-Since or ETag headers.

## Install

``` shell

composer require "knplabs/valid=~0.1@dev"

```

## Use

``` php

<?php

class CustomRule implements Knp\Valid\Rule\LastModified, Knp\Valid\Rule\ETag
{
    public function supports(Request $request)
    {
        return true;
    }

    public function getETag(Request $request)
    {
        return 'something';
    }

    public function getLastModified(Request $request)
    {
        return new \DateTime;
    }
}

$kernel = new AppCache(
    new Knp\Valid\Kernel(
        new AppKernel('prod', false),
        new Knp\Valid\ResponseManipulator(array(
            new Knp\Valid\Doctrine\ListenerRule,
            new CustomRule,
        ))
    )
);

$kernel->handle(Request::createFromGlobals())->send();

```

## Contribute


``` shell

composer install --dev --prefer-dist

vim features/**/*.feature
behat
phpspec desc Knp\Valid\*
phpspec run

```

