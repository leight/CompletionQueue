<?php

class CompletionQueueTest extends PHPUnit_Framework_TestCase
{
    public function testEnqueuingIncrementsCount()
    {
        $queue = new CompletionQueue();

        $before = count($queue);

        $queue->enqueue('test');
        $after = count($queue);

        $this->assertSame($before + 1, $after);
    }

    public function testDequeuingDecrementsCount()
    {
        $queue = new CompletionQueue();

        $ticket = $queue->enqueue('test');
        $before = count($queue);

        $queue->complete($ticket);
        $queue->dequeue();
        $after = count($queue);

        $this->assertSame($before - 1, $after);
    }

    public function testTicketIsNumericKey()
    {
        $queue = new CompletionQueue();

        $ticket = $queue->enqueue('test');
        $this->assertInternalType('int', $ticket);
    }

    public function testCannotEnqueueNullValue()
    {
        $this->setExpectedException('UnexpectedValueException');

        $queue = new CompletionQueue();
        $queue->enqueue(null);
    }

    public function testDequeuingWithNoCompleteItemsReturnsNull()
    {
        $queue = new CompletionQueue();
        $queue->enqueue('test1');
        $queue->enqueue('test2');
        $queue->enqueue('test3');

        $this->assertNull($queue->dequeue());
    }

    public function testDequeuingWithPartialCompleteReturnsOnlyCompletedItems()
    {
        $queue = new CompletionQueue();

        $ticket1 = $queue->enqueue('test1');
        $ticket2 = $queue->enqueue('test2');
        $ticket3 = $queue->enqueue('test3');

        $count = count($queue);
        $this->assertSame(3, $count);

        $queue->complete($ticket2);
        $this->assertNull($queue->dequeue());

        $count = count($queue);
        $this->assertSame(3, $count);

        $queue->complete($ticket1);
        $this->assertSame('test1', $queue->dequeue());
        $this->assertSame('test2', $queue->dequeue());
        $this->assertNull($queue->dequeue());

        $count = count($queue);
        $this->assertSame(1, $count);

        $queue->complete($ticket3);
        $this->assertSame('test3', $queue->dequeue());
        $this->assertNull($queue->dequeue());

        $count = count($queue);
        $this->assertSame(0, $count);
    }
}
 