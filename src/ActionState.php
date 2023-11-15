<?php

declare(strict_types=1);

namespace Kiboko\Components\Action;

use Kiboko\Contract\Action\StateInterface;

final class ActionState implements StateInterface
{
    /** @var array<string, int> */
    private array $metrics = [
        'started' => false,
        'success' => false,
        'failure' => false,
    ];

    public function __construct(
        private readonly StateInterface $decorated,
    ) {
    }

    public function initialize(): void
    {
        $this->metrics['started'] = true;
        $this->decorated->initialize();
    }

    public function success(): void
    {
        $this->metrics['success'] = true;
        $this->decorated->success();
    }

    public function failure(int $step = 1): void
    {
        $this->metrics['failure'] = true;
        $this->decorated->failure();
    }

    public function observeAccept(): callable
    {
        return fn () => $this->metrics['success'];
    }

    public function observeReject(): callable
    {
        return fn () => $this->metrics['failure'];
    }

    public function teardown(): void
    {
        $this->decorated->teardown();
    }
}
