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

        $consumer = new ChildConsumer();
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

        $consumer = new ChildConsumer();
        self::assertTrue($consumer->_isMainRequest($event));
    }

    /**
     * @test
     */
    public function it_throws_exception_if_events_main_request_test_does_not_return_bool(): void
    {
        $this->expectException(\Throwable::class);

        $request = new Request();

        $kernel = $this->prophesize(HttpKernelInterface::class);

        $event = new class($kernel->reveal(), $request, HttpKernelInterface::MASTER_REQUEST) extends KernelEvent {
            /** @psalm-suppress InvalidReturnType */
            public function isMainRequest(): bool
            {
                /** @psalm-suppress InvalidReturnStatement */
                return 'no';
            }
        };

        $consumer = new ChildConsumer();
        $consumer->_isMainRequest($event);
    }
}

class Consumer
{
    use MainRequestTrait;
}

/**
 * The reason for the inheritance is that we then make sure the trait works in child classes
 */
class ChildConsumer extends Consumer
{
    public function _getMainRequestFromRequestStack(RequestStack $requestStack): ?Request
    {
        return $this->getMainRequestFromRequestStack($requestStack);
    }

    public function _isMainRequest(KernelEvent $event): bool
    {
        return $this->isMainRequest($event);
    }
}
