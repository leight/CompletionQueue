PHP Completion Queue
====================

This is a queue that only allows dequeuing of items flagged as complete. It is useful when performing asynchronous/parallel processing where items may complete out of order, but must have additional processing performed in order.

Enqueued items are assigned a ticket (a simple integer) which must be completed before that item and all subsequent items are able to be dequeued. The ticketing approach was chosen to avoid heavy array searching when completing items.

Class synopsis:
---------------

### Class definition

**`CompletionQueue implements Countable`**

### Methods

**`public int   enqueue(mixed $item)`**

Accepts any type except `null` as an item to be placed in the queue. An `UnexpectedValueException` is thrown if `null` is passed. Returns a ticket number the enqueued item.

**`public mixed dequeue()`**

Removes an item from the queue and returns it. If nothing is available to be dequeued then `null` is returned.

**`public void  complete(int $ticket)`**

Marks a queued item as complete, allowing it to be dequeued in the future.

Example:
--------

Imagine a queue where the numbers 0 to 9 have been queued in order. If we alternate between completing a random entry and dequeing all possible completed entries, the output might resemble the following.

```
Complete: 9            // Nothing to dequeue until "0" is completed
Complete: 1            // "1" will be immediately available too when "0" is completed
Complete: 6
Complete: 7
Complete: 8
Complete: 3
Complete: 4
Complete: 0            // Items can start being dequeued
Dequeued: 0 1          // "2" has not yet been completed
Complete: 2
Dequeued: 2 3 4        // "5" has not yet been completed
Complete: 5
Dequeued: 5 6 7 8 9 
```
