<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Rollback extends Command {
        protected string $identifier = 'ansible:rollback';
        protected string $description = 'Rollback to an earlier version of the project via ansible';
        protected string $help = 'Usage: ezekiel ansible:rollback';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $root_dir = $app->dir_root();
            $app->execute("ansible-playbook -i $root_dir/ansible/inventory.yaml $root_dir/ansible/playbooks/rollback.yaml");
        }
    }

