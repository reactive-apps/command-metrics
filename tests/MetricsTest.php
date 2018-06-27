<?php declare(strict_types=1);

namespace ReactiveApps\Tests\Command\Metrics\Command;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use ReactiveApps\Command\Metrics\Command\Metrics;
use ReactiveApps\Rx\Shutdown;
use WyriHaximus\React\Inspector\Metric;
use WyriHaximus\React\Inspector\Metrics as InspectorMetrics;
use Psr\Log\LoggerInterface;
use WyriHaximus\React\Inspector\MetricsStreamInterface;

final class MetricsTest extends TestCase
{
    public function testMetrics()
    {
        //gc_collect_cycles();

        $metrics = [];

        $loop = Factory::create();
        $metricsStream = new InspectorMetrics($loop, ['ticks'], 1.0);
        $logger = $this->prophesize(LoggerInterface::class);
        $shutdown = new Shutdown();

        $loop->futureTick(function () use ($metricsStream, &$metrics, $shutdown, $logger) {
            (new Metrics($metricsStream, function (Metric $metric) use (&$metrics) {
                $metrics[] = $metric;
            }, $shutdown, $logger->reveal()))();
        });
        $loop->addTimer(5, function () use ($shutdown) {
            $shutdown->onCompleted();
        });
        $loop->run();
        //self::assertSame(0, gc_collect_cycles());

        self::assertCount(111, $metrics);
        foreach ($metrics as $metric) {
            self::assertInstanceOf(Metric::class, $metric);
        }
    }

    public function testMetricsError()
    {
        //gc_collect_cycles();

        $metrics = [];

        $loop = Factory::create();
        $metricsStream = new TestMetricsStream();
        $logger = $this->prophesize(LoggerInterface::class);
        $shutdown = new Shutdown();

        $loop->futureTick(function () use ($metricsStream, &$metrics, $shutdown, $logger) {
            (new Metrics($metricsStream, function (Metric $metric) use (&$metrics) {
                $metrics[] = $metric;
            }, $shutdown, $logger->reveal()))();
        });
        $loop->addTimer(5, function () use ($shutdown) {
            $shutdown->onCompleted();
        });
        $loop->run();
        //self::assertSame(0, gc_collect_cycles());

        self::assertCount(111, $metrics);
        foreach ($metrics as $metric) {
            self::assertInstanceOf(Metric::class, $metric);
        }
    }
}
