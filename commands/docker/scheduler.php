<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Scheduler extends Command {
        protected string $identifier = 'docker:scheduler';
        protected string $description = 'Run scheduler inside Docker container';
        protected string $help = 'Usage: ezekiel docker:scheduler';
        protected array $dependencies = [ 'docker' , 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            passthru("docker exec -u 0 -it $container php /app/native/bin/scheduler.php");
        }
    }

