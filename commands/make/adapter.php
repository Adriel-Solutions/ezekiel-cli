<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Adapter extends Command {
        protected string $identifier = 'make:adapter';
        protected string $description = 'Create a new adapter';
        protected string $help = 'Usage: ezekiel make:adapter <name>';

        public function run(Application $app, ?string ...$args): void
        {
            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel make:adapter <name>');
                return;
            }

            $name = ucfirst($args[0]);

            $variables = [
                'PLURAL' => $name,
            ];

            $output = $app->fill_template('/ezekiel/adapter.php', $variables);

            $app->output_file(
                $app->dir_app() . "/adapters/" . strtolower($name) . '.php',
                $output
            );
        }
    }


