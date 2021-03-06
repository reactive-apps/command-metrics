<?php declare(strict_types=1);

namespace ReactiveApps\Command\Metrics\Listener;

use Psr\Log\LoggerInterface;
use ReactInspector\MetricsStreamInterface;
use ReactiveApps\Command\Metrics\HandlerInterface;

final class Metrics
{
    /** @var MetricsStreamInterface */
    private $metrics;

    /** @var HandlerInterface */
    private $handler;

    /** @var LoggerInterface */
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
        $this->logger = $logger;
    }

    public function __invoke(): void
    {
        $this->metrics->onComplete();
        $this->logger->debug('Stopped the metrics stream');
        $this->handler->shutdown();
        $this->logger->debug('Shutdown the metrics handler');
    }
}
