<?php

use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use ReactiveApps\Command\Metrics\Command\Metrics;
use ReactiveApps\Rx\Shutdown;
use WyriHaximus\React\Inspector\Metrics as InspectorMetrics;
use WyriHaximus\React\Inspector\MetricsStreamInterface;

return [
    MetricsStreamInterface::class => \DI\factory(function (
        LoopInterface $loop
    ) {
        return new InspectorMetrics($loop, ['totals', 'ticks', 'io'], 1.0);
    }),
    Metrics::class => \DI\factory(function (
        MetricsStreamInterface $metrics,
        callable $handler,
        Shutdown $shutdown,
        LoggerInterface $logger
    ) {
        return new Metrics($metrics, $handler, $shutdown, $logger);
    })
    ->parameter('handler', \DI\get('config.metrics.handler')),
];
