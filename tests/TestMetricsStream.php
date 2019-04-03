<?php declare(strict_types=1);

namespace ReactiveApps\Tests\Command\Metrics\Command;

use Rx\Subject\Subject;
use WyriHaximus\React\Inspector\MetricsStreamInterface;

final class TestMetricsStream extends Subject implements MetricsStreamInterface
{
}
