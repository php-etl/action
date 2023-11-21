<?php

declare(strict_types=1);

namespace Kiboko\Component\Action;

use Kiboko\Contract\Action\StateInterface;

final class ActionState implements StateInterface
{
    private string $status = 'pending';

    public function initialize(): void
    {
        $this->status = 'running';
    }

    public function success(): void
    {
        $this->status = 'success';
    }

    public function failure(): void
    {
        $this->status = 'failure';
    }

    public function observeState(): callable
    {
        return fn () => $this->status;
    }
}
