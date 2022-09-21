<?php

    namespace commands;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Version extends Command {
        protected string $identifier = 'version';
        protected string $description = 'Output cli version';
        protected string $help = 'Usage: ezekiel version';

        public function run(Application $app, ?string ...$args): void
        {
            $app->output("Ezekiel " . Constants::$VERSION);
        }
    }


