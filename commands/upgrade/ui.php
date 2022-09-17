<?php

    namespace commands\upgrade;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Ui extends Command {
        protected string $identifier = 'upgrade:ui';
        protected string $description = 'Upgrade the framework\'s ui components by downloading the newest remote code';
        protected string $help = 'Usage: ezekiel upgrade:ui';
        protected array $dependencies = [ 'curl' , 'svn' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            $app->execute('mkdir native-old');
            $app->execute('cp -r native native-old');
            $app->execute('rm -rf native');
            $app->execute('svn export --quiet ' . Constants::$GIT_REPOSITORY_URL . '.git/trunk/native');
            $app->execute('rm -rf native-old');

            $app->output('Updated successfully');
        }
    }


