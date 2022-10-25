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

            $script = $app->fill_template('/scripts/docker/job.php', [ 'ID' => $id ]);
            $container = $app->execute("cat docker-compose.dev.yml | grep -E \"container_name: .*-fpm\" | sed -E 's/container_name://' | tr -d '\" '");
            $output = $app->run_docker_script($container, $script);

            $job = json_decode($output, true);

            // Equivalent of JS Object.entries(...)
            $new_job = [];
            foreach($job as $k => $v)
                $new_job[] = [ $k , $v ?? 'None' ];

            $app->output_table(
                [ 'Attribute' , 'Value' ],
                $new_job
            );
        }
    }

