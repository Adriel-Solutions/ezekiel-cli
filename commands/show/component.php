<?php

    namespace commands\show;

    use classes\Command;
    use classes\Application;

    class Component extends Command {
        protected string $identifier = 'show:component';
        protected string $description = 'Show all the details of a ui component';
        protected string $help = 'Usage: ezekiel show:component <name>';
        protected array $dependencies = [ 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            if(empty($args)) {
                $app->output('Missing arguments');
                $app->output('Usage: ezekiel show:component <name>');
                return;
            }

            $name = strtolower($args[0]);

            $raw = $app->execute("cat native/views/components/$name.php | tr '\n' ' ' | grep '<?php .* Parameters .*\*\/' --color -o");
            $lines = explode(' ', $raw);
            var_dump($lines);
        }
    }


