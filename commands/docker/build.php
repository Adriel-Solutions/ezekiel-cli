<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Build extends Command {
        protected string $identifier = 'docker:build';
        protected string $description = 'Build a local Docker image to run the Ezekiel project';
        protected string $help = 'Usage: ezekiel docker:build';
        protected array $dependencies = [ 'docker' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $templates_dir = $app->dir_templates();
            passthru("DOCKER_BUILDKIT=0 docker build -t ezekiel/boilerplate-php-fpm -f $templates_dir/docker/Dockerfile .");
        }
    }



