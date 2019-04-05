<?php declare(strict_types=1);

namespace ReactiveApps\Command\Metrics\Command;

use Psr\Log\LoggerInterface;
use ReactiveApps\Command\Command;
use ReactiveApps\Command\Metrics\HandlerInterface;
use WyriHaximus\PSR3\CallableThrowableLogger\CallableThrowableLogger;
use WyriHaximus\PSR3\ContextLogger\ContextLogger;
use WyriHaximus\React\Inspector\MetricsStreamInterface;

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
     * @param HandlerInterface       $handler
     * @param LoggerInterface        $logger
     */
    public function __construct(MetricsStreamInterface $metrics, HandlerInterface $handler, LoggerInterface $logger)
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

    public function __invoke(): void
    {
        $this->metrics->subscribe([$this->handler, 'handle'], CallableThrowableLogger::create($this->logger));
    }
}
