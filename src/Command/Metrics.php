<?php declare(strict_types=1);

namespace ReactiveApps\Command\Metrics\Command;

use Psr\Log\LoggerInterface;
use function React\Promise\resolve;
use ReactInspector\MetricsStreamInterface;
use ReactiveApps\Command\Command;
use ReactiveApps\Command\Metrics\HandlerInterface;
use ReactiveApps\LifeCycleEvents\Promise\Shutdown;
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
     * @var Shutdown
     */
    private $shutdown;

    /**
     * @param MetricsStreamInterface $metrics
     * @param HandlerInterface       $handler
     * @param LoggerInterface        $logger
     * @param Shutdown               $shutdown
     */
    public function __construct(MetricsStreamInterface $metrics, HandlerInterface $handler, LoggerInterface $logger, Shutdown $shutdown)
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
        $this->shutdown = $shutdown;
    }

    public function __invoke()
    {
        $this->metrics->subscribe([$this->handler, 'handle'], CallableThrowableLogger::create($this->logger));

        yield resolve($this->shutdown);

        return 0;
    }
}
