<?php

require __DIR__ . '/../vendor/autoload.php';

$queue = new CompletionQueue();

$items = range(0, 19);
$tickets = [];

foreach ($items as $item) {
    $tickets[] = $queue->enqueue($item);
}

shuffle($tickets);

while (($ticket = array_shift($tickets)) !== null) {
    print "Complete: $ticket\n";
    $queue->complete($ticket);
    print "Dequeued: ";
    while (($item = $queue->dequeue()) !== null) {
        print "$item ";
    }
    print "\n";
}