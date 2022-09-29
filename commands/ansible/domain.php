<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Domain extends Command {
        protected string $identifier = 'ansible:domain';
        protected string $description = 'Setup the SSL certificate for your domain, with Nginx';
        protected string $help = 'Usage: ezekiel ansible:domain';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $root_dir = $app->dir_root();
            passthru("ansible-playbook -i $root_dir/ansible/inventory.yaml $root_dir/ansible/playbooks/domain.yaml");
        }
    }

