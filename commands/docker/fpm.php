<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Fpm extends Command {
        protected string $identifier = 'docker:shell';
        protected string $description = 'Open a bash shell into the Ezekiel fpm container';
        protected string $help = 'Usage: ezekiel docker:fpm';
        protected array $dependencies = [ 'docker' , 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            passthru("docker exec -w /app -u 0 -it $container sh");
        }
    }





