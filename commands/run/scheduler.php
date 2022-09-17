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
            $root_dir = $app->dir_root();
            $app->execute("php $root_dir/native/bin/scheduler.php");
        }
    }

