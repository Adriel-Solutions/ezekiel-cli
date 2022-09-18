<?php

    namespace commands;

    use classes\Application;
    use classes\Command;

    class Listing extends Command {
        protected string $identifier = 'list';
        protected string $description = 'List all the available commands';
        protected string $help = 'Usage: ezekiel list';

        public function run(Application $app, ?string ...$args): void
        {
            $commands = $app->get_commands();

            $app->output("List of available commands:");

            $app->output_table(
                [ 'Command' , 'Description' ],
                array_map(
                    fn($c) => [ $c->get_identifier() , $c->get_description() ],
                    iterator_to_array($commands)
                )
            );

            $app->output("\nUse `ezekiel help <command>` to display help specific to a command");
        }
    }
