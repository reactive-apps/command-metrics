<?php declare(strict_types=1);

use Psr\Container\ContainerInterface;
use React\EventLoop\LoopInterface;
use WyriHaximus\React\Inspector\Metrics as InspectorMetrics;
use WyriHaximus\React\Inspector\MetricsStreamInterface;

return [
    MetricsStreamInterface::class => \DI\factory(function (
        LoopInterface $loop,
        ContainerInterface $container
    ) {
        return new InspectorMetrics($loop, ['totals', 'ticks', 'io'], 1.0, $container);
    }),
];
