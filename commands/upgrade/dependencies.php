<?php

    namespace commands\upgrade;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Dependencies extends Command {
        protected string $identifier = 'upgrade:dependencies';
        protected string $description = 'Upgrade dependencies via Composer';
        protected string $help = 'Usage: ezekiel upgrade:dependencies';
        protected array $dependencies = [ 'composer' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            $metadata_current = json_decode(file_get_contents('./composer.json'), true);
            $metadata_future = json_decode($app->execute('curl -s https://raw.githubusercontent.com/Adriel-Solutions/ezekiel/master/composer.json'), true);

            $version_current = $metadata_current['version'];
            $version_future = $metadata_future['version'];

            $app->output('--');
            $app->output('Current version is : ' . $version_current);
            $app->output('Newest version is : ' . $version_future);
            $app->output('--');

            if($version_current === $version_future) {
                $app->output('Already on the latest version');
                return;
            }

            $app->execute('mkdir native-old');
            $app->execute('cp -r native native-old');
            $app->execute('rm -rf native');
            $app->execute('svn export --quiet ' . Constants::$GIT_REPOSITORY_URL . '.git/trunk/native');
            $app->execute('rm -rf native-old');
            $app->execute("sed -i '' 's/$version_current/$version_future/' composer.json");

            $app->output('Updated successfully');
        }
    }



