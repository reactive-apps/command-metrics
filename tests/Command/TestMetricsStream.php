<?php declare(strict_types=1);

namespace ReactiveApps\Tests\Command\Metrics\Command;

use ReactInspector\MetricsStreamInterface;
use Rx\Subject\Subject;

final class TestMetricsStream extends Subject implements MetricsStreamInterface
{
}
