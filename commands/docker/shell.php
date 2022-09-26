<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Shell extends Command {
        protected string $identifier = 'docker:shell';
        protected string $description = 'Open a bash shell into the Ezekiel web container';
        protected string $help = 'Usage: ezekiel docker:shell';
        protected array $dependencies = [ 'docker' , 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-web\" | sed -E 's/container_name://' | tr -d '\" '");
            passthru("docker exec -it $container bash");
        }
    }





