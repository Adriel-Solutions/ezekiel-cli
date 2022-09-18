<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Controller extends Command {
        protected string $identifier = 'make:controller';
        protected string $description = 'Create a new controller';
        protected string $help = 'Usage: ezekiel make:controller <name>';

        public function run(Application $app, ?string ...$args): void
        {
            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel make:controller <name>');
                return;
            }

            $name = ucfirst($args[0]);

            $variables = [
                'PLURAL' => $name,
            ];

            $output = $app->fill_template('/ezekiel/controller.php', $variables);

            $app->output_file(
                $app->dir_app() . "/controllers/" . strtolower($name) . '.php',
                $output
            );
        }
    }


