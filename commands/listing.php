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

            $longest_command_size = 0;
            foreach($commands as $identifier => $c) {
                if(strlen($identifier) <= $longest_command_size) continue;
                $longest_command_size = strlen($identifier);
            }

            $word_size = 5;
            $tab_count = ceil($longest_command_size / $word_size) + 1;
            foreach($commands as $identifier => $c) {
                $identifier_size = ceil ( strlen($identifier) / $word_size ) * $word_size;
                $tab_need = $tab_count - ceil ( $identifier_size / $word_size ) + 1;

                $app->output("  " . $identifier . str_repeat("\t", $tab_need) . $c->get_description());
            }

            $app->output("\nUse `ezekiel help <command>` to display help specific to a command");
        }
    }
