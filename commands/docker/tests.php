<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Tests extends Command {
        protected string $identifier = 'docker:tests';
        protected string $description = 'Run PHPUnit tests inside Docker container';
        protected string $help = 'Usage: ezekiel docker:tests';
        protected array $dependencies = [ 'docker' , 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            $output = $app->execute("docker exec -u 0 -it $container /app/dependencies/bin/phpunit -c /app/setup/scripts/phpunit.xml --testdox");
            $app->output($output);
        }
    }






