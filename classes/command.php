<?php

    namespace classes;

    class Command {
        protected string $identifier = "";
        protected string $description = "";
        protected string $help = "";
        protected array $dependencies = [];

        public function run(Application $app, ?string ...$args) : void {  }

        public function get_identifier() : string
        {
            return $this->identifier;
        }

        public function get_description() : string
        {
            return $this->description;
        }

        public function get_help() : string
        {
            return $this->help;
        }

        public function get_dependencies() : array
        {
            return $this->dependencies;
        }

        public function exit_if_missing_dependencies(Application $app) : void
        {
            if(empty($this->dependencies)) return;

            foreach($this->dependencies as $d) {
                if($app->is_available($d)) continue;

                $app->output('Missing dependencies');
                $app->output('This command requires ' . $d . ' to be installed');
                die();
            }
        }
    }
