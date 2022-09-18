<?php

    namespace commands\upgrade;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Dependencies extends Command {
        protected string $identifier = 'upgrade:dependencies';
        protected string $description = 'Upgrade dependencies via Composer';
        protected string $help = 'Usage: ezekiel upgrade:dependencies';

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $app->execute('composer update');
        }
    }



