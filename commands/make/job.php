<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Job extends Command {
        protected string $identifier = 'make:job';
        protected string $description = 'Create a new job';
        protected string $help = 'Usage: ezekiel make:job <name>';

        public function run(Application $app, ?string ...$args): void
        {
            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel make:job <name>');
                return;
            }

            $name = ucfirst($args[0]);

            $variables = [
                'NAME' => $name,
            ];

            $output = $app->fill_template('/ezekiel/job.php', $variables);

            $app->output_file(
                $app->dir_app() . "/jobs/" . strtolower($name) . '.php',
                $output
            );
        }
    }



