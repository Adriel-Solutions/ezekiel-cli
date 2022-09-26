<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Migrations extends Command {
        protected string $identifier = 'docker:migrations';
        protected string $description = 'Run database migrations inside the proper Docker container';
        protected string $help = 'Usage: ezekiel docker:migrations';
        protected array $dependencies = [ 'docker' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            passthru("docker exec -u 0 -it $container php /app/native/scripts/run-db-migrations.php");
        }
    }

