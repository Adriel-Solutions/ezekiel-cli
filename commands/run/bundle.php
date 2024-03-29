<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Bundle extends Command {
        protected string $identifier = 'run:bundle';
        protected string $description = 'Run Tailwind/Alpine build';
        protected string $help = 'Usage: ezekiel run:bundle';
        protected array $dependencies = [ 'tailwind' , 'uglifyjs' , 'lightningcss' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            // Tailwind
            if(!$app->is_tailwind_disabled()) {
                $output = $app->execute("tailwind -i ./native/views/assets/styles/style.min.css -o ./native/views/assets/styles/tw.css");
                $app->output($output);
            }

            // Lightningcss
            if(!$app->is_tailwind_disabled())
                $output = $app->execute("lightningcss --minify --bundle --targets 'cover 99.5%' ./native/views/assets/styles/tw.css > ./native/views/assets/styles/tw.min.css");
            else 
                $output = $app->execute("lightningcss --minify --bundle --targets 'cover 99.5%' ./app/views/assets/styles/style.css > ./app/views/assets/styles/style.min.css");

            // Alpine scripts - App
            $output = $app->execute("find ./app/ -type f -name \"*[^min].js\" -print | xargs -I {} echo {} | sed 's/...$//' | xargs -I {} uglifyjs {}.js --output {}.min.js");
            $app->output($output);

            // Alpine scripts - Native
            $output = $app->execute("find ./native/views/assets/scripts/components/ -type f -name \"*[^min].js\" -print | xargs -I {} echo {} | sed 's/...$//' | xargs -I {} uglifyjs {}.js --output {}.min.js");
            $output = $app->execute("uglifyjs ./native/views/assets/scripts/core.js --output ./native/views/assets/scripts/core.min.js");
            $app->output($output);
        }
    }

