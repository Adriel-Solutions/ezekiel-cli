<?php

    namespace commands\install;

    use classes\application;
    use classes\command;
    use classes\constants;

    class Ui extends command {
        protected string $identifier = 'install:ui';
        protected string $description = 'install the remote ui library inside the project by downloading its code';
        protected string $help = 'usage: ezekiel install:ui';
        protected array $dependencies = [ 'git' ];

        public function run(application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            if(file_exists("./app/views/components/input.php")) {
                $app->output("The user interface is installed already");
                $app->output("Upgrade with: ezekiel upgrade:ui");
                return;
            }

            $app->execute('git clone ' . Constants::$GIT_ORGANIZATION_URL . '/ezekiel-ui.git ./app');
            $app->execute('rm -rf ./app/.git');

            $app->output("The user interface library was installed");
        }
    }

