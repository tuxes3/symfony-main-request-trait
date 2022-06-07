<?php

declare(strict_types=1);

namespace Setono\MainRequestTrait\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\MainRequestTrait\MainRequestTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @covers \MainRequestTrait
 */
final class MainRequestTraitTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_returns_request_from_request_stack(): void
    {
        $mainRequest = new Request();
        $subRequest = new Request();
        $requestStack = new RequestStack();
        $requestStack->push($mainRequest);
        $requestStack->push($subRequest);

        $consumer = new Consumer();
        self::assertSame($mainRequest, $consumer->_getMainRequestFromRequestStack($requestStack));
    }

    /**
     * @test
     */
    public function it_returns_true_when_event_is_from_main_request(): void
    {
        $request = new Request();

        $kernel = $this->prophesize(HttpKernelInterface::class);

        $event = new KernelEvent($kernel->reveal(), $request, HttpKernelInterface::MASTER_REQUEST);

        $consumer = new Consumer();
        self::assertTrue($consumer->_isMainRequest($event));
    }
}

final class Consumer
{
    use MainRequestTrait;

    public function _getMainRequestFromRequestStack(RequestStack $requestStack): ?Request
    {
        return $this->getMainRequestFromRequestStack($requestStack);
    }

    public function _isMainRequest(KernelEvent $event): bool
    {
        return $this->isMainRequest($event);
    }
}
