<?php

declare(strict_types=1);

namespace Kiboko\Component\Action;

use Kiboko\Contract\Action\ActionInterface;
use Kiboko\Contract\Action\ExecutingActionInterface;
use Kiboko\Contract\Action\RunnableInterface;
use Kiboko\Contract\Action\StateInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Action implements ExecutingActionInterface, RunnableInterface
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

    public function run(): int
    {
        return 1;
    }
}
