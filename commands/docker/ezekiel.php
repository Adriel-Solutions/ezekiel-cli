<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Ezekiel extends Command {
        protected string $identifier = 'docker:ezekiel';
        protected string $description = 'Run an Ezekiel command inside the web Docker container';
        protected string $help = 'Usage: ezekiel docker:ezekiel <command>';
        protected array $dependencies = [ 'docker' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel docker:ezekiel <command>');
                return;
            }

            $command = join(' ', $args);

            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-db\" | sed -E 's/container_name://' | tr -d '\" '");
            passthru("docker exec -w/app -u 0 -it $container ./ezekiel $command");
        }
    }




