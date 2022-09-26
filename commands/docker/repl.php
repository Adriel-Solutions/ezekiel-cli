<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Repl extends Command {
        protected string $identifier = 'docker:repl';
        protected string $description = 'Open a psql repl inside the PG Docker container';
        protected string $help = 'Usage: ezekiel docker:repl';
        protected array $dependencies = [ 'docker' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-db\" | sed -E 's/container_name: \"(.+)\"$/\1/' | tr -d ' '");
            $app->execute("docker exec -it --user postgres $container psql project");
        }
    }




