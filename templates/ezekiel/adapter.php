<?php

    namespace app\adapters;

    use native\libs\Adapter;

    class <PLURAL> extends Adapter {
        // List of aliases ( a -> b )
        protected array $mappers = [];

        // List of exclusions ( a -> a disappears )
        protected array $excluders = [];

        // List of dynamic values ( a -> function of the object that's got a )
        protected array $computers = [];
    }

