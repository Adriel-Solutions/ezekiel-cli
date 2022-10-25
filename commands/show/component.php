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

            $content = file_get_contents("native/views/components/$name.php");
            $lines = explode("\n", $content);

            $relevant_lines = [];
            $block_started = false;
            foreach($lines as $l) {
                if(str_contains($l, '* Parameters :')) {
                    $block_started = true;
                    continue;
                }

                if(!$block_started)
                    continue;

                if($block_started && str_contains($l, '?>'))
                    break;

                if(str_contains($l, '* - '))
                    $relevant_lines[] = $l;
            }

            var_dump($relevant_lines);
        }
    }


