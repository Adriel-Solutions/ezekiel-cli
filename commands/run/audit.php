<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Audit extends Command {
        protected string $identifier = 'run:audit';
        protected string $description = 'Run Psalm evaluation on the code';
        protected string $help = 'Usage: ezekiel run:audit';

        public function run(Application $app, ?string ...$args): void
        {
            $root_dir = $app->dir_root();
            $output = $app->execute("$root_dir/dependencies/bin/psalm --no-progress -c $root_dir/setup/scripts/psalm.xml");
            $app->output($output);
        }
    }

