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
            $root_dir = $app->dir_root();
            $output = $app->execute("php $root_dir/native/scripts/run-db-migrations.php");
            $app->output($output);
        }
    }
