<?php

declare(strict_types=1);

namespace Kiboko\Component\Action;

use Kiboko\Contract\Action\StateInterface;

final class ActionState implements StateInterface
{
    private string $status = 'pending';

    public function __construct(
        private readonly StateInterface $decorated
    ) {
    }

    public function initialize(): void
    {
        $this->status = 'running';
        $this->decorated?->initialize();
    }

    public function success(): void
    {
        $this->status = 'success';
        $this->decorated?->success();
    }

    public function failure(): void
    {
        $this->status = 'failure';
        $this->decorated?->failure();
    }

    public function observeState(): callable
    {
        return fn () => $this->status;
    }
}
