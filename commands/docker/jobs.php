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

            $script = <<<'EOF'
                require './native/index.php';
                $service = default_service('jobs');
                $jobs = $service->get_all([ 'order' => [ 'pk' => 'ASC' ] ]);
                var_dump($jobs);
            EOF;

            $handle = tmpfile();

            $handle_uri = stream_get_meta_data($handle)['uri'];
            $new_handle_uri = $handle_uri . '.php';
            $handle_filename = pathinfo($new_handle_uri, PATHINFO_FILENAME);

            fwrite($handle, $script);
            move_uploaded_file($handle_uri, $new_handle_uri);


            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            $app->execute("docker cp $new_handle_uri $container:/tmp/$handle_filename");
            $output = $app->execute("docker exec -w /app -it $container sh -c 'php -f /tmp/$handle_filename'");
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


