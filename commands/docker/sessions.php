<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Sessions extends Command {
        protected string $identifier = 'docker:sessions';
        protected string $description = 'Display sessions inside Docker FPM container';
        protected string $help = 'Usage: ezekiel docker:sessions';
        protected array $dependencies = [ 'docker' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            $output = $app->execute("docker exec -it $container sh -c 'cat /tmp/sess*'");
            $app->output($output);
        }
    }

