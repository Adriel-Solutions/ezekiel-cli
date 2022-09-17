<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Provision extends Command {
        protected string $identifier = 'ansible:provision';
        protected string $description = 'Provision a brand new server';
        protected string $help = 'Usage: ezekiel ansible:provision';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $root_dir = $app->dir_root();
            $app->execute("ansible-playbook -i $root_dir/ansible/inventory.yaml $root_dir/ansible/playbooks/provision.yaml");
        }
    }

