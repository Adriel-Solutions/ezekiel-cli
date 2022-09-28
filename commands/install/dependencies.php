<?php

    namespace commands\install;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Dependencies extends Command {
        protected string $identifier = 'install:dependencies';
        protected string $description = 'Install dependencies via Composer';
        protected string $help = 'Usage: ezekiel install:dependencies';

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $app->execute('composer install');
        }
    }




