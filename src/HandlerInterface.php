<?php declare(strict_types=1);

namespace ReactiveApps\Command\Metrics;

use ReactInspector\Metric;

interface HandlerInterface
{
    public function handle(Metric $metric): void;

    public function shutdown(): void;
}
