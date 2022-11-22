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
            switch(PHP_OS_FAMILY) {
                case 'Linux':
                    $app->execute('ezekiel completion > /usr/share/bash-completion/completions/ezekiel');
                break;

                case 'Darwin':
                case 'OSX':
                    $app->execute('ezekiel completion > /usr/local/etc/bash_completion.d/ezekiel');
                break;
            }
        }
    }
