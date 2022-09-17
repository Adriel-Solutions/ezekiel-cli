<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Tests extends Command {
        protected string $identifier = 'run:tests';
        protected string $description = 'Run core tests';
        protected string $help = 'Usage: ezekiel run:tests';

        public function run(Application $app, ?string ...$args): void
        {
            $app->execute("./setup/scripts/docker/run-db-migrations.sh");
        }
    }

