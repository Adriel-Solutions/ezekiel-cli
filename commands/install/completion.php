<?php

    namespace commands\install;

    use classes\application;
    use classes\command;
    use classes\constants;

    class Completion extends command {
        protected string $identifier = 'install:completion';
        protected string $description = 'Install the completion globally on Mac';
        protected string $help = 'Usage: ezekiel install:completion';

        public function run(application $app, ?string ...$args): void
        {
            $app->execute('ezekiel completion > /usr/local/etc/bash_completion.d/ezekiel');
        }
    }



