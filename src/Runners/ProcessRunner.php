<?php


namespace Lukasss93\PdfToPpm\Runners;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Alchemy\BinaryDriver\ProcessRunnerInterface;
use Psr\Log\LoggerInterface;
use SplObjectStorage;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

class ProcessRunner implements ProcessRunnerInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $name;

    public function __construct(LoggerInterface $logger = null, $name = null)
    {
        $this->logger = $logger;
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     *
     * @return ProcessRunner
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    public function run(Process $process, SplObjectStorage $listeners, $bypassErrors)
    {
        if ($this->logger !== null) {
            $this->logger->info(sprintf(
                '%s running command %s', $this->name, $process->getCommandLine()
            ));
        }

        try {
            $process->run($this->buildCallback($listeners));
        } catch (RuntimeException $e) {
            if (!$bypassErrors) {
                $this->doExecutionFailure($process->getCommandLine(), $process->getErrorOutput(), $e);
            }
        }


        if (!$bypassErrors && !$this->isProcessSuccessful($process)) {
            $this->doExecutionFailure($process->getCommandLine(), $process->getErrorOutput());
        } else if (!$this->isProcessSuccessful($process) && $this->logger !== null) {
            $this->logger->error($this->createErrorMessage($process->getCommandLine(), $process->getErrorOutput()));
            return;
        } else {
            if ($this->logger !== null) {
                $this->logger->info(sprintf('%s executed command successfully', $this->name));
            }
            return $this->getProcessOutput($process);
        }
    }

    private function buildCallback(SplObjectStorage $listeners)
    {
        return function ($type, $data) use ($listeners) {
            foreach ($listeners as $listener) {
                $listener->handle($type, $data);
            }
        };
    }

    private function doExecutionFailure($command, $errorOutput, \Exception $e = null)
    {
        if ($this->logger !== null) {
            $this->logger->error($this->createErrorMessage($command, $errorOutput));
        }
        throw new ExecutionFailureException($this->name, $command, $errorOutput,
            $e ? $e->getCode() : 0, $e ?: null);
    }

    private function createErrorMessage($command, $errorOutput)
    {
        return sprintf('%s failed to execute command %s: %s', $this->name, $command, $errorOutput);
    }

    protected function isProcessSuccessful(Process $process): bool
    {
        $code = $process->getExitCode();
        return $code === 0 || $code === 1;
    }

    protected function getProcessOutput(Process $process): string
    {
        switch ($process->getExitCode()) {
            case 0:
                $output=$process->getOutput();
                return $output!==''?$output:$process->getErrorOutput();
            case 1:
            default:
                return $process->getErrorOutput();
        }
    }
}
