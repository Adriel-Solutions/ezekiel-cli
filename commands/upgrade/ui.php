<?php

    namespace commands\upgrade;

    use classes\application;
    use classes\command;
    use classes\constants;

    class Ui extends command {
        protected string $identifier = 'upgrade:ui';
        protected string $description = 'Upgrade the local ui library by downloading a newer version from git';
        protected string $help = 'usage: ezekiel upgrade:ui';
        protected array $dependencies = [ 'git' ];

        public function run(application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            if(!file_exists("./native/views/components/")) {
                $app->output("The user interface is not installed yet");
                $app->output("Install with: ezekiel install:ui");
                return;
            }

            $app->execute('mkdir ./native/views-old');
            $app->execute('cp -r ./native/views native/views-old');
            $app->execute('rm -rf ./native/views');
            $app->execute('git clone ' . Constants::$GIT_ORGANIZATION_URL . '/ezekiel-ui.git ./native/ui');
            $app->execute('rm -rf ./native/ui/.git');
            $app->execute('mv ./native/ui/views/* ./native/ui/');
            $app->execute('mv ./native/ui ./native/views');
            $app->execute('rm -rf ./native/views-old');
            $app->execute('rm -rf ./native/views/views');

            $app->output("The user interface library was updated");
        }
    }


