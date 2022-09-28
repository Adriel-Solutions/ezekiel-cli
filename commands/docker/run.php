<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Run extends Command {
        protected string $identifier = 'docker:run';
        protected string $description = 'Run the Docker stack using the compose file';
        protected string $help = 'Usage: ezekiel docker:run';
        protected array $dependencies = [ 'docker' , 'docker-compose' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            passthru("docker-compose -f docker-compose.dev.yml down");
            passthru("docker-compose -f docker-compose.dev.yml up -d");
        }
    }







