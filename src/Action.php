<?php

declare(strict_types=1);

namespace Kiboko\Component\Action;

use Kiboko\Contract\Action\ActionInterface;
use Kiboko\Contract\Action\ExecutingActionInterface;
use Kiboko\Contract\Action\StateInterface;
use Kiboko\Contract\Satellite\RunnableInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

readonly class Action implements ExecutingActionInterface, RunnableInterface
{
    public function __construct(
        private LoggerInterface $logger = new NullLogger(),
    ) {
    }

    public function execute(ActionInterface $action, StateInterface $state): ExecutingActionInterface
    {
        $state->initialize();

        try {
            $action->execute();

            $state->success();
        } catch (\Exception $exception) {
            $state->failure();

            $this->logger->critical(
                $exception->getMessage(),
                [
                    'previous' => $exception->getPrevious(),
                ]
            );
        }

        $state->teardown();

        return $this;
    }

    public function run(int $interval = 1000): int
    {
        return 1;
    }
}
