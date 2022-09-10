<?php

    namespace classes;

    use ArrayAccess;

    class Commands implements ArrayAccess {
        private array $arr;

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
    }
