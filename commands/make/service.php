<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Service extends Command {
        protected string $identifier = 'make:service';
        protected string $description = 'Create a new service';
        protected string $help = 'Usage: ezekiel make:service <name>';

        public function run(Application $app, ?string ...$args): void
        {
            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel make:service <name>');
                return;
            }

            $name = ucfirst($args[0]);

            $variables = [
                'SINGULAR' => rtrim($name, "s"),
                'PLURAL' => $name,
                'TABLE' => strtolower($name)
            ];

            $output = $app->fill_template('/ezekiel/service.php', $variables);

            var_dump($output);
        }
    }

