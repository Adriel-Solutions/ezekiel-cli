<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Bundle extends Command {
        protected string $identifier = 'run:bundle';
        protected string $description = 'Run Tailwind/Alpine build';
        protected string $help = 'Usage: ezekiel run:bundle';
        protected array $dependencies = [ 'tailwind' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            // Tailwind
            $output = $app->execute("tailwind -i ./native/views/assets/styles/style.min.css -o ./native/views/assets/styles/tw.min.css");
            $app->output($output);

            // Alpine
            $output = $app->execute("find ./app/ ./native/views/assets/scripts/components/ -type f -name \"*.js\" -exec cat {} \; > native/views/assets/scripts/bundle.min.js");
            $app->output($output);
        }
    }

