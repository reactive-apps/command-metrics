<?php declare(strict_types=1);

namespace ReactiveApps\Command\Metrics\Command;

use WyriHaximus\React\Inspector\MetricsStreamInterface;
use Psr\Log\LoggerInterface;
use ReactiveApps\Command\Command;
use ReactiveApps\Rx\Shutdown;
use WyriHaximus\PSR3\CallableThrowableLogger\CallableThrowableLogger;
use WyriHaximus\PSR3\ContextLogger\ContextLogger;

final class Metrics implements Command
{
    const COMMAND = 'metrics';

    /**
     * @var MetricsStreamInterface
     */
    private $metrics;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param MetricsStreamInterface $metrics
     * @param callable $handler
     * @param LoggerInterface $logger
     */
    public function __construct(MetricsStreamInterface $metrics, callable $handler, LoggerInterface $logger)
    {
        $this->metrics = $metrics;
        $this->handler = $handler;
        $this->logger = new ContextLogger(
            $logger,
            [
                'command' => 'metrics',
            ],
            'command-metrics'
        );
    }

    public function __invoke()
    {
        $this->metrics->subscribe($this->handler, CallableThrowableLogger::create($this->logger));
    }
}
