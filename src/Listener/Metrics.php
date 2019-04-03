<?php declare(strict_types=1);

namespace ReactiveApps\Command\Metrics\Listener;

use Psr\Log\LoggerInterface;
use WyriHaximus\React\Inspector\MetricsStreamInterface;

final class Metrics
{
    /** @var MetricsStreamInterface */
    private $metrics;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param MetricsStreamInterface $metrics
     * @param LoggerInterface        $logger
     */
    public function __construct(MetricsStreamInterface $metrics, LoggerInterface $logger)
    {
        $this->metrics = $metrics;
        $this->logger = $logger;
    }

    public function __invoke(): void
    {
        $this->metrics->onComplete();
        $this->logger->debug('Stopped the metrics stream');
    }
}
