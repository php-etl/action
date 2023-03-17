<?php

declare(strict_types=1);

namespace Kiboko\Component\Action;

use Kiboko\Contract\Action\ActionInterface;
use Kiboko\Contract\Action\ExecutingActionInterface;
use Kiboko\Contract\Action\RunnableInterface;
use Kiboko\Contract\Action\StateInterface;

class Action implements ExecutingActionInterface, RunnableInterface
{
    public function execute(ActionInterface $action, StateInterface $state): ExecutingActionInterface
    {
        $state->initialize();

        try {
            $action->execute();

            $state->accept();
        } catch (\Exception $exception) {
            $state->reject();

            // TODO : improve this error management, maybe use a logger ?
            throw new \Exception($exception->getMessage());
        }

        $state->teardown();

        return $this;
    }

    public function run(): int
    {
        return 1;
    }
}
