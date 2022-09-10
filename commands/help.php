<?php

    namespace commands;

    use classes\Application;
    use classes\Command;

    class Help extends Command {
        protected string $identifier = 'help';

        public function run(Application $app, ?string ...$args): void
        {
            $app->cls();

            $app->output("ezekiel(1)\t\t\t\t\t\tManual\t\t\t\t\t\tezekiel(1)");

            $app->output('NAME');
            $app->output("\tezekiel\n");

            $app->output('SYNOPSIS');
            $app->output("\tphp ezekiel command [arguments]\n");

            $app->output('DESCRIPTION');
            $app->output(
                <<<EOD
                    \tThe ezekiel command-line interface enables you to quickly setup, automate, and scaffold code 
                    \tinside a PHP project based on the ezekiel framework.

                    \tEvery command follows the syntax `verb:topic arguments`

                    \tYou can find all the supported commands and features by calling `php ezekiel list`. This will
                    \ttrigger the `list` command and show a brief summary of each and every ezekiel command.

                    \tIn the backend, what the ezekiel command-line interface does is run other shell commands on 
                    \tyour behalf, and it displays them prior to execution so you know what's going on.\n
                EOD
            );

            $app->output('EXAMPLES');
            $app->output(
                <<<EOD
                    \tDisplay all the available commands in the ezekiel command-line interface

                    \t\tphp ezekiel list

                    \tDownload and setup the auth module inside the current project

                    \t\tphp ezekiel install:module auth

                    \tRun database migrations

                    \t\tphp ezekiel run:migrations

                    \tSet up and run all the Docker containers to serve the project

                    \t\tphp ezekiel run:app

                    \tScaffold a brand new module named `profile` inside the project

                    \t\tphp ezekiel create:module profile

                    \tList all the current jobs and show their state

                    \t\tphp ezekiel list:jobs

                    \tRun all pending jobs

                    \t\tphp ezekiel run:jobs

                    \tYou can find all the supported commands and features by calling `php ezekiel list`. This will
                    \ttrigger the `list` command and show a brief summary of each and every ezekiel command.\n
                EOD
            );

            $app->output('EXIT STATUS');
            $app->output(
                <<<EOD
                    \tThe ezekiel command-line interface exits 0 on success, and >0 if an error occurs.\n
                EOD
            );

            $app->output('AUTHORS');
            $app->output(
                <<<EOD
                    \tAdriel Solutions <it@adriel.solutions>\n
                EOD
            );

            $app->output('VERSION');
            $app->output("\t1.0\n");
        }
    }
