<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Repl extends Command {
        protected string $identifier = 'run:repl';
        protected string $description = 'Open a psql repl inside the PG database';
        protected string $help = 'Usage: ezekiel run:repl';
        protected array $dependencies = [ 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            passthru("sudo -u postgres psql project");
        }
    }
