<?php

    namespace commands;

    use classes\Application;
    use classes\Command;

    class Completion extends Command {
        protected string $identifier = 'completion';
        protected string $description = 'Output bash autocompletion script';
        protected string $help = '[LINUX] -> Usage: ezekiel completion > /etc/bash_completion.d/ezekiel'
                                ."\n[MAC]   -> Usage: esekiel completion > /usr/local/etc/bash_completion.d/ezekiel";

        public function run(Application $app, ?string ...$args): void
        {
            $identifiers = array_keys(iterator_to_array($app->get_commands()));
            $basic = array_filter($identifiers, fn($i) => !str_contains($i, ":"));
            $compound = array_filter($identifiers, fn($i) => str_contains($i, ":"));
            $switches = [ '--dry-run' , '--quiet' ];

            $suggestions = join(' ', $basic) . ' ' . join(' ', $compound) . join(' ', $switches);
            $app->output($app->fill_template("/bash/completion", [ 'SUGGESTIONS' => $suggestions ]));
        }
    }

