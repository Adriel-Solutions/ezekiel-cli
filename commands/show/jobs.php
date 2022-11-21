<?php

    namespace commands\show;

    use classes\Command;
    use classes\Application;

    class Jobs extends Command {
        protected string $identifier = 'show:jobs';
        protected string $description = 'List all the pending and executed jobs';
        protected string $help = 'Usage: ezekiel show:jobs';
        protected array $dependencies = [ 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            $script = $app->fill_template('/scripts/docker/jobs.php', []);
            $output = $app->run_script($script);

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

