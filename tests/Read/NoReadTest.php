<?php

namespace Jasny\DB\Tests\Read;

use Improved\IteratorPipeline\PipelineBuilder;
use Jasny\DB\QueryBuilder\QueryBuilderInterface;
use Jasny\DB\Read\NoRead;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jasny\DB\Read\NoRead
 */
class NoReadTest extends TestCase
{
    public function testWithQueryBuilder()
    {
        $builder = $this->createMock(QueryBuilderInterface::class);

        $base = new NoRead();
        $ret = $base->withQueryBuilder($builder);

        $this->assertSame($base, $ret);
    }

    public function testWithResultBuilder()
    {
        $base = new NoRead();
        $ret = $base->withResultBuilder(new PipelineBuilder());

        $this->assertSame($base, $ret);
    }

    /**
     * @expectedException \Jasny\DB\Exception\UnsupportedFeatureException
     */
    public function testFetch()
    {
        $reader = new NoRead();

        $reader->fetch([], ['id' => 42]);
    }
    
    /**
     * @expectedException \Jasny\DB\Exception\UnsupportedFeatureException
     */
    public function testCount()
    {
        $reader = new NoRead();

        $reader->count([], ['id' => 42]);
    }
}