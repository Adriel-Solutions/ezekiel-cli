<?php

    namespace classes;

    use ArrayAccess;
    use Iterator;

    class Commands implements ArrayAccess, Iterator {
        private array $arr;
        private int $position = 0;

        public function __construct() {
            $this->arr = array();
        }

        public function offsetSet($offset, $value) : void
        {
            if (is_null($offset)) {
                $this->arr[] = $value;
            } else {
                $this->arr[$offset] = $value;
            }
        }

        public function offsetExists($offset) : bool
        {
            return isset($this->arr[$offset]);
        }

        public function offsetUnset($offset) : void
        {
            unset($this->arr[$offset]);
        }

        public function offsetGet($offset) : Command
        {
            return isset($this->arr[$offset]) ? $this->arr[$offset] : null;
        }

        public function rewind() : void
        {
            reset($this->arr);
        }

        public function current() : mixed
        {
            return current($this->arr);
        }

        public function key() : mixed
        {
            return key($this->arr);
        }

        public function next() : void
        {
            next($this->arr);
        }

        public function valid() : bool
        {
            return key($this->arr) !== null;
        }
    }
