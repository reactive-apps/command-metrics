<?php declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use ReactiveApps\Command\Metrics\Command\Metrics;
use WyriHaximus\React\Inspector\Metrics as InspectorMetrics;
use WyriHaximus\React\Inspector\MetricsStreamInterface;

return [
    MetricsStreamInterface::class => \DI\factory(function (
        LoopInterface $loop,
        ContainerInterface $container
    ) {
        return new InspectorMetrics($loop, ['totals', 'ticks', 'io'], 1.0, $container);
    }),
    Metrics::class => \DI\factory(function (
        MetricsStreamInterface $metrics,
        callable $handler,
        LoggerInterface $logger
    ) {
        return new Metrics($metrics, $handler, $logger);
    })
        ->parameter('handler', \DI\get('config.metrics.handler')),
];
