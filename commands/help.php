<?php

    namespace commands;

    use classes\Application;
    use classes\Command;
    use Error;

    class Help extends Command {
        protected string $identifier = 'help';
        protected string $description = 'Display help and examples';
        protected string $help = 'Usage: ezekiel help [command]';

        public function run(Application $app, ?string ...$args): void
        {
            // ezekiel help for command
            if(!empty($args)) {
                $class = '\\commands\\' . str_replace(':', '\\', $args[0]);

                try {
                    $instance = new $class();
                    $app->output($instance->get_description());
                    $app->output($instance->get_help());
                    $app->output('Depends on: ' . (join(', ', $instance->get_dependencies()) ?: 'none'));
                } catch(Error $e) {
                    $app->output('Unknown command ' . $args[0]);
                }

                return;
            }

            // ezekiel actual help display
            $display = "ezekiel(1)\t\t\t\t\t\tManual\t\t\t\t\t\tezekiel(1)\n\n";

            $display .= "NAME\n";
            $display .= "\tezekiel\n\n";

            $display .= "SYNOPSIS\n";
            $display .= "\tezekiel command [arguments]\n\n";

            $display .= "DESCRIPTION\n";
            $display .=
                <<<EOD
                    \tThe ezekiel command-line interface enables you to quickly setup, automate, and scaffold code 
                    \tinside a PHP project based on the ezekiel framework.

                    \tEvery command follows the syntax `verb:topic arguments`

                    \tYou can find all the supported commands and features by calling `ezekiel list`. This will
                    \ttrigger the `list` command and show a brief summary of each and every ezekiel command.

                    \tIn the backend, what the ezekiel command-line interface does is run other shell commands on 
                    \tyour behalf, and it displays them prior to execution so you know what's going on.\n\n
                EOD;
            

            $display .= "EXAMPLES\n";
            $display .=
                <<<EOD
                    \tDisplay all the available commands in the ezekiel command-line interface

                    \t\tezekiel list

                    \tDownload and setup the auth module inside the current project

                    \t\tezekiel install:module auth

                    \tRun database migrations

                    \t\tezekiel run:migrations

                    \tSet up and run all the Docker containers to serve the project

                    \t\tezekiel run:app

                    \tScaffold a brand new module named `profile` inside the project

                    \t\tezekiel create:module profile

                    \tList all the current jobs and show their state

                    \t\tezekiel list:jobs

                    \tRun all pending jobs

                    \t\tezekiel run:jobs

                    \tYou can find all the supported commands and features by calling `ezekiel list`. This will
                    \ttrigger the `list` command and show a brief summary of each and every ezekiel command.\n\n
                EOD;
            

            $display .= "EXIT STATUS\n";
            $display .=
                <<<EOD
                    \tThe ezekiel command-line interface exits 0 on success, and >0 if an error occurs.\n\n
                EOD;
            

            $display .= "AUTHORS\n";
            $display .=
                <<<EOD
                    \tAdriel Solutions <it@adriel.solutions>\n\n
                EOD;
            

            $display .= "VERSION\n";
            $display .= "\t1.0\n\n";

            $app->output_with_pagination($display);
        }
    }
