<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Router extends Command {
        protected string $identifier = 'make:router';
        protected string $description = 'Create a new router';
        protected string $help = 'Usage: ezekiel make:router <name>';

        public function run(Application $app, ?string ...$args): void
        {
            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel make:router <name>');
                return;
            }

            $name = ucfirst($args[0]);

            $variables = [
                'PLURAL' => $name,
            ];

            $output = $app->fill_template('/ezekiel/router.php', $variables);

            $app->output_file(
                $app->dir_app() . "/routers/" . strtolower($name) . '.php',
                $output
            );
        }
    }




