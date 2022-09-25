<?php

    namespace commands;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Ssh extends Command {
        protected string $identifier = 'ssh';
        protected string $description = 'Connect to the remote host';
        protected string $help = 'Usage: ezekiel ssh';
        protected array $dependencies = [ 'sed' ];

        public function run(Application $app, ?string ...$args): void
        {
            $host = $app->execute("sed -n 3p ansible/inventory.yaml | tr -d  ' :'");
            passthru("ssh $host");
        }
    }
