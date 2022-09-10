<?php

    namespace classes;

    class Command {
        protected string $identifier;
        protected string $description;

        public function run(Application $app, ?string ...$args) : void {  }

        public function get_identifier() : string
        {
            return $this->identifier;
        }

        public function get_description() : string
        {
            return $this->description;
        }

    }
