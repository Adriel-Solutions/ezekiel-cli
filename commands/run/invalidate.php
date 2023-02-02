<?php

    namespace commands\run;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Invalidate extends Command {
        protected string $identifier = 'run:invalidate';
        protected string $description = 'Delete all the cache files generated at runtime';
        protected string $help = 'Usage: ezekiel run:invalidate';
        protected array $dependencies = [ 'find' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            $app->execute('find ./storage/app/cache/ -type f -name *.html -delete');

            $app->output('Cache invalidation finished');
        }
    }

