<?php

    namespace commands\make;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Form extends Command {
        protected string $identifier = 'make:form';
        protected string $description = 'Create a new Javascript form script';
        protected string $help = 'Usage: ezekiel make:script';

        public function run(Application $app, ?string ...$args): void
        {
            $output = $app->fill_template('/ezekiel/form.js', []);

            $app->output_file(
                $app->dir_app() . "/views/sample.js",
                $output
            );
        }
    }




