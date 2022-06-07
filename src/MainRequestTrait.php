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
        $request = null;

        if (method_exists($requestStack, 'getMainRequest')) {
            $request = $requestStack->getMainRequest();
        } elseif (method_exists($requestStack, 'getMasterRequest')) {
            /** @var Request|null $request */
            $request = $requestStack->getMasterRequest();
        }

        return $request;
    }

    protected function isMainRequest(KernelEvent $event): bool
    {
        if (method_exists($event, 'isMainRequest')) {
            return $event->isMainRequest();
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
