<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Bundle extends Command {
        protected string $identifier = 'run:bundle';
        protected string $description = 'Run Tailwind/Alpine build';
        protected string $help = 'Usage: ezekiel run:bundle';
        protected array $dependencies = [ 'tailwind' , 'uglifyjs' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            // Tailwind
            $output = $app->execute("tailwind -i ./native/views/assets/styles/style.min.css -o ./native/views/assets/styles/tw.min.css");
            $app->output($output);

            // Alpine scripts - App
            $output = $app->execute("find ./app/ -type f -name \"*.js\" -print | xargs -I {} echo {} | sed 's/..$//' | xargs -I {} uglifyjs {}.js --output {}.min.js");
            $app->output($output);

            // Alpine scripts - Native
            $output = $app->execute("find ./native/views/assets/scripts/components/ -type f -name \"*.js\" -print | xargs -I {} echo {} | sed 's/..$//' | xargs -I {} uglifyjs {}.js --output {}.min.js");
            $output = $app->execute("uglifyjs ./native/views/assets/scripts/core.js --output ./native/views/assets/scripts/core.min.js");
            $app->output($output);
        }
    }

