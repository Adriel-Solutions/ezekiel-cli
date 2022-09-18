<?php

    namespace commands\upgrade;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Cli extends Command {
        protected string $identifier = 'upgrade:cli';
        protected string $description = 'Upgrade the framework\'s CLI via composer';
        protected string $help = 'Usage: ezekiel upgrade:cli';

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $app->execute('composer update adrielsolutions/ezekiel-cli');
        }
    }



