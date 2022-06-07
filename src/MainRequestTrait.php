<?php

declare(strict_types=1);

namespace Setono\MainRequestTrait;

use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\KernelEvent;

trait MainRequestTrait
{
    protected function getMainRequestFromRequestStack(RequestStack $requestStack): ?Request
    {
        if (method_exists($requestStack, 'getMainRequest')) {
            /** @var Request|null $request */
            $request = $requestStack->getMainRequest();

            return $request;
        }

        if (method_exists($requestStack, 'getMasterRequest')) {
            /** @var Request|null $request */
            $request = $requestStack->getMasterRequest();

            return $request;
        }

        throw new LogicException(sprintf(
            'Neither the method %s::getMainRequest nor the method %s::getMasterRequest exists on the request stack object. This should not be possible.',
            RequestStack::class,
            RequestStack::class
        ));
    }

    protected function isMainRequest(KernelEvent $event): bool
    {
        if (method_exists($event, 'isMainRequest')) {
            /** @var bool $res */
            $res = $event->isMainRequest();

            return $res;
        }

        if (method_exists($event, 'isMasterRequest')) {
            /** @var bool $res */
            $res = $event->isMasterRequest();

            return $res;
        }

        throw new LogicException(sprintf(
            'Neither the method %s::isMainRequest nor the method %s::isMasterRequest exists on the event object. This should not be possible.',
            KernelEvent::class,
            KernelEvent::class
        ));
    }
}
