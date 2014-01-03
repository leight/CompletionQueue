<?php

class CompletionQueue implements Countable
{
    private $items = [];
    private $complete = [];

    public function enqueue($item)
    {
        if (is_null($item)) {
            throw new UnexpectedValueException('Cannot queue null values (dequeue() returns null on failure)');
        }
        $this->items[] = $item;
        end($this->items);
        return key($this->items);
    }

    public function dequeue()
    {
        $result = null;

        reset($this->items);
        $ticket = key($this->items);

        if (isset($this->complete[$ticket])) {
            $result = $this->items[$ticket];
            unset(
                $this->complete[$ticket],
                $this->items[$ticket]
            );
        }

        return $result;
    }

    public function complete($ticket)
    {
        $this->complete[$ticket] = true;
    }

    public function count()

        return count($this->items);
    }
}
 