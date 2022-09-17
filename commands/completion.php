<?php

    namespace commands;

    use classes\Application;
    use classes\Command;

    class Completion extends Command {
        protected string $identifier = 'completion';
        protected string $description = 'Output bash autocompletion';
        protected string $help = 'Usage: ezekiel completion';

        public function run(Application $app, ?string ...$args): void
        {
        }
    }

