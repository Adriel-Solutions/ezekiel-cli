<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Tailwind extends Command {
        protected string $identifier = 'run:tailwind';
        protected string $description = 'Run Tailwind build';
        protected string $help = 'Usage: ezekiel run:tailwind';
        protected array $dependencies = [ 'tailwind' ];

        public function run(Application $app, ?string ...$args): void
        {
            $output = $app->execute("tailwind -i ./native/views/assets/styles/style.min.css -o ./native/views/assets/styles/tw.min.css");
            $app->output($output);
        }
    }

