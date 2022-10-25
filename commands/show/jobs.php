<?php

    namespace commands\show;

    use classes\Command;
    use classes\Application;

    class Jobs extends Command {
        protected string $identifier = 'show:jobs';
        protected string $description = 'List all the pending and executed jobs';
        protected string $help = 'Usage: ezekiel show:jobs';

        public function run(Application $app, ?string ...$args): void
        {

            /* $app->output_table( */
            /*     [ 'Job' , 'Status' , 'Last run at' ], */
            /*     array_map( */
            /*         fn($m) => [ $m['name'] , $m['version'] , $m['activated'] ? 'Yes' : 'No' ], */
            /*         $modules, */
            /*     ) */
            /* ); */
        }
    }

