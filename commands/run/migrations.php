<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Migrations extends Command {
        protected string $identifier = 'run:migrations';
        protected string $description = 'Run database migration files';
        protected string $help = 'Usage: ezekiel run:migrations';

        public function run(Application $app, ?string ...$args): void
        {
            $app->execute("./setup/scripts/docker/run-db-migrations.sh");
        }
    }
