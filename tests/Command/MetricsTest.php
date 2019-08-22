<?php declare(strict_types=1);

namespace ReactiveApps\Tests\Command\Metrics\Command;

use ApiClients\Tools\TestUtilities\TestCase;
use Psr\Log\LoggerInterface;
use React\EventLoop\Factory;
use ReactiveApps\Command\Metrics\Command\Metrics;
use ReactiveApps\Command\Metrics\HandlerInterface;
use ReactiveApps\LifeCycleEvents\Promise\Shutdown;
use Recoil\React\ReactKernel;
use WyriHaximus\React\Inspector\Metric;
use WyriHaximus\React\Inspector\Metrics as InspectorMetrics;

/**
 * @internal
 */
final class MetricsTest extends TestCase
{
    public function testMetrics(): void
    {
        //gc_collect_cycles();

        $metricsHandler = new class() implements HandlerInterface {
            public $metrics = [];

            public function handle(Metric $metric): void
            {
                $this->metrics[] = $metric;
            }

            public function shutdown(): void
            {
                // void
            }
        };

        $loop = Factory::create();
        $kernel = ReactKernel::create($loop);
        $metricsStream = new InspectorMetrics($loop, ['ticks'], 1.0);
        $logger = $this->prophesize(LoggerInterface::class);
        $shutdown = new Shutdown();

        $kernel->execute(function () use ($metricsStream, $metricsHandler, $logger, $shutdown) {
            yield (new Metrics($metricsStream, $metricsHandler, $logger->reveal(), $shutdown))();
        });
        $loop->addTimer(5, function () use ($loop, $shutdown): void {
            $shutdown();
            $loop->stop();
        });
        $loop->run();
        //self::assertSame(0, gc_collect_cycles());

        self::assertCount(112, $metricsHandler->metrics);
        foreach ($metricsHandler->metrics as $metric) {
            self::assertInstanceOf(Metric::class, $metric);
        }
    }
}
