<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Ping extends Command {
        protected string $identifier = 'ansible:ping';
        protected string $description = 'Ping every host in Ansible inventory';
        protected string $help = 'Usage: ezekiel ansible:ping';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' ];

        public function run(Application $app, ?string ...$args): void
        {
            $root_dir = $app->dir_root();
            $result = $app->execute("ansible all -m ping -i $root_dir/ansible/inventory.yaml");
            $app->output($result);
        }
    }


