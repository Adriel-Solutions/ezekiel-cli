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
                <?php
                require './native/index.php';
                $service = default_service('jobs');
                $jobs = $service->get_all([ 'order' => [ 'pk' => 'ASC' ] ]);
                print(json_encode($jobs));
            EOF;

            $handle = tmpfile();

            $handle_uri = stream_get_meta_data($handle)['uri'];
            $new_handle_uri = $handle_uri . '.php';
            $handle_filename = pathinfo($new_handle_uri, PATHINFO_BASENAME);

            fwrite($handle, $script);
            rename($handle_uri, $new_handle_uri);

            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            $app->execute("docker cp $new_handle_uri $container:/tmp/$handle_filename");
            $output = $app->execute("docker exec -w /app -it $container sh -c 'php -f /tmp/$handle_filename'");

            $jobs = json_decode($output, true);
            $app->output_table(
                [ 'ID' , 'Job' , 'Running' , 'For' , 'Frequency' , 'From' ,'Last' ],
                array_map(
                    fn($j) => [
                        $j['pk'] ,
                        $j['class'],
                        $j['is_running'] ? 'Yes' : 'No',
                        $j['scheduled_for'] ?? 'None',
                        $j['schedule_frequency'] ?? 'None',
                        $j['scheduled_from'] ?? 'None',
                        $j['last_run_at'] ?? 'None',
                    ],
                    $jobs,
                )
            );
        }
    }
