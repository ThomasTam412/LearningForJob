<?php
class Counter {
    public $count = 0;

    public function increment() { $this->count++; }

    public function decrement() { $this->count--; }

    public function reset() { $this->count = 0; }

    public function getCount() { return $this->count; }
}

$c = new Counter();
$c->increment();
$c->increment();
$c->increment();
$c->decrement();
echo $c->getCount();  // 應該印 2

$c->reset();
echo $c->getCount();  // 應該印 0

?>