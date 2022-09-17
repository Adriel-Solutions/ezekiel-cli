<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Module extends Command {
        protected string $identifier = 'make:module';
        protected string $description = 'Scaffold a new module';
        protected string $help = 'Usage: ezekiel make:module';
        protected array $dependencies = [ ];

        public function run(Application $app, ?string ...$args): void
        {
        }
    }
