<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Deploy extends Command {
        protected string $identifier = 'ansible:deploy';
        protected string $description = 'Deploy the project for the first time';
        protected string $help = 'Usage: ezekiel ansible:deploy';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $root_dir = $app->dir_root();
            passthru("ansible-playbook -i $root_dir/ansible/inventory.yaml $root_dir/ansible/playbooks/deploy.yaml");
        }
    }

