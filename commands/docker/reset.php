<?php

    namespace commands\docker;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Reset extends Command {
        protected string $identifier = 'docker:reset';
        protected string $description = 'Resets the Docker database container using default db.sql';
        protected string $help = 'Usage: ezekiel docker:reset';
        protected array $dependencies = [ 'docker' , 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-db\" | sed -E 's/container_name://' | tr -d '\" '");
            $app->execute("docker exec -it --user postgres $container psql -c 'DROP DATABASE project WITH (FORCE);'");
            $app->execute("docker exec -it --user postgres $container psql -f /docker-entrypoint-initdb.d/init.sql");
        }
    }






