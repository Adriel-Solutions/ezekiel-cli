<?php

    namespace commands\docker;

    use classes\Command;
    use classes\Application;

    class Job extends Command {
        protected string $identifier = 'docker:job';
        protected string $description = 'Show all the variables of a job';
        protected string $help = 'Usage: ezekiel docker:job <ID>';
        protected array $dependencies = [ 'docker' , 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel docker:job <ID>');
                return;
            }

            $id = $args[0];

            $script = <<<'EOF'
                <?php
                require './native/index.php';
                $service = default_service('jobs');
            EOF
                .'$job = $service->get(' . $id . ');'
                .<<<'EOF'
                print(json_encode($job));
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

            $job = json_decode($output, true);

            // Equivalent of JS Object.entries(...)
            $new_job = [];
            foreach($job as $k => $v)
                $new_job[] = [ $k , $v ];

            $app->output_table(
                [ 'Attribute' , 'Value' ],
                $new_job
            );
        }
    }

