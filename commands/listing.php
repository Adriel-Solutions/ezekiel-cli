<?php

    namespace commands;

    use classes\Application;
    use classes\Command;

    class Listing extends Command {
        protected string $identifier = 'list';

        public function run(Application $app, ?string ...$args): void
        {
            echo 'WIP';
        }
    }
