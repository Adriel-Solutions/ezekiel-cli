<?php

    namespace commands\show;

    use classes\Command;
    use classes\Application;

    class Components extends Command {
        protected string $identifier = 'show:components';
        protected string $description = 'Show all the components installed';
        protected string $help = 'Usage: ezekiel show:components';
        protected array $dependencies = [ 'sed' , 'tr' , 'grep' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            $files = explode("\n", $app->execute('ls native/views/components'));
            $components = [];

            foreach($files as $file) {
                $content = file_get_contents("native/views/components/$file");
                $lines = explode("\n", $content);

                foreach($lines as $l) {
                    if(!str_contains($l, '* Name :'))
                        continue;

                    $name = trim(explode(":", $l)[1]);
                    $components[] = [ $name , $file ];
                    break;
                }
            }

            $app->output_with_pagination(
                $app->to_table(
                    [ 'Name' , 'File' ],
                    $components
                )
            );
        }
    }


