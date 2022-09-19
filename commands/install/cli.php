<?php

    namespace commands\install;

    use classes\application;
    use classes\command;
    use classes\constants;

    class Cli extends command {
        protected string $identifier = 'install:cli';
        protected string $description = 'Install the CLI globally';
        protected string $help = 'Usage: ezekiel install:cli';

        public function run(application $app, ?string ...$args): void
        {
            $output = $app->fill_template('/bash/cli', []);
            $app->execute("echo \"$output\" > /usr/local/bin/ezekiel");
            $app->execute("chmod 755 /usr/local/bin/ezekiel");
        }
    }


