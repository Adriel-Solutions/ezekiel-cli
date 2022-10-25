<?php

    namespace commands\docker;

    use classes\Command;
    use classes\Application;

    class Jobs extends Command {
        protected string $identifier = 'docker:jobs';
        protected string $description = 'List all the pending and executed jobs';
        protected string $help = 'Usage: ezekiel docker:jobs';
        protected array $dependencies = [ 'docker' , 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {

            $this->exit_if_missing_dependencies($app);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-db\" | sed -E 's/container_name://' | tr -d '\" '");
            $output = $app->execute("docker exec -it --user postgres $container psql -c 'SELECT * FROM jobs'");
            var_dump($output);
            /* $app->output_table( */
            /*     [ 'Job' , 'Status' , 'Last run at' ], */
            /*     array_map( */
            /*         fn($m) => [ $m['name'] , $m['version'] , $m['activated'] ? 'Yes' : 'No' ], */
            /*         $modules, */
            /*     ) */
            /* ); */
        }
    }


