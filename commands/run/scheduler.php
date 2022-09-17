<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Scheduler extends Command {
        protected string $identifier = 'run:scheduler';
        protected string $description = 'Run all the pending jobs via the internal scheduler';
        protected string $help = 'Usage: ezekiel run:scheduler';

        public function run(Application $app, ?string ...$args): void
        {
            $app->execute("./setup/scripts/docker/run-db-migrations.sh");
        }
    }

