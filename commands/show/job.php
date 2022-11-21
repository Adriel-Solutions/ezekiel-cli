<?php

    namespace commands\show;

    use classes\Command;
    use classes\Application;

    class Job extends Command {
        protected string $identifier = 'show:job';
        protected string $description = 'Show all the variables of a job';
        protected string $help = 'Usage: ezekiel show:job <ID>';
        protected array $dependencies = [ 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel show:job <ID>');
                return;
            }

            $id = $args[0];

            $script = $app->fill_template('/scripts/docker/job.php', [ 'ID' => $id ]);
            $output = $app->run_script($script);

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


