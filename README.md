# Symfony Main Request Trait

[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Mutation testing][ico-infection]][link-infection]

A convenience library for library maintainers of Symfony bundles or libraries using Symfony components. When Symfony
changed the naming from `master` to `main` in multiple places this had the consequence that certain methods were deprecated
in Symfony v5 and removed in v6.

This library will let you support Symfony v4-v6 and not even think about the renaming :)

## Installation

```shell
composer require setono/symfony-main-request-trait
```

## Usage

**Example with the `RequestStack`**
```php
<?php

declare(strict_types=1);

use Setono\MainRequestTrait\MainRequestTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class YourService
{
    use MainRequestTrait;

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function action(): void
    {
        /**
         * This is how you get the main request from the RequestStack. No need to worry about master/main, just get it
         * @var Request|null $request
         */
        $request = $this->getMainRequestFromRequestStack($this->requestStack);

        // do something with the request
    }
}
```

**Example with event subscriber**

```php
<?php

declare(strict_types=1);

use Setono\MainRequestTrait\MainRequestTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class YourSubscriber implements EventSubscriberInterface
{
    use MainRequestTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'handle'
        ];
    }

    public function handle(KernelEvent $event): void
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        // Now we know we are dealing with the main request
    }
}
```

[ico-version]: https://poser.pugx.org/setono/symfony-main-request-trait/v/stable
[ico-license]: https://poser.pugx.org/setono/symfony-main-request-trait/license
[ico-github-actions]: https://github.com/Setono/symfony-main-request-trait/workflows/build/badge.svg
[ico-code-coverage]: https://codecov.io/gh/Setono/symfony-main-request-trait/branch/master/graph/badge.svg
[ico-infection]: https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FSetono%2Fsymfony-main-request-trait%2Fmaster

[link-packagist]: https://packagist.org/packages/setono/symfony-main-request-trait
[link-github-actions]: https://github.com/Setono/symfony-main-request-trait/actions
[link-code-coverage]: https://codecov.io/gh/Setono/symfony-main-request-trait
[link-infection]: https://dashboard.stryker-mutator.io/reports/github.com/Setono/symfony-main-request-trait/master
