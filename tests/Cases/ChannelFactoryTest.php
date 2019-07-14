<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Task\Cases;

use Hyperf\Task\ChannelFactory;
use PHPUnit\Framework\TestCase;
use Swoole\Coroutine\Channel;

/**
 * @internal
 * @coversNothing
 */
class ChannelFactoryTest extends TestCase
{
    public function testChannelPop()
    {
        $facotry = new ChannelFactory();

        $channel = $facotry->get(1);

        $this->assertInstanceOf(Channel::class, $channel);
        $this->assertTrue($facotry->has(1));

        $id = uniqid();
        $result = parallel([function () use ($facotry) {
            return $facotry->pop(1);
        }, function () use ($channel, $id) {
            $channel->push($id);
        }]);

        $this->assertSame($id, $result[0]);
    }
}
