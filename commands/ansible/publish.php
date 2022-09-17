<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Publish extends Command {
        protected string $identifier = 'ansible:publish';
        protected string $description = 'Publish a new version of the project';
        protected string $help = 'Usage: ezekiel ansible:publish';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);
            $root_dir = $app->dir_root();
            $app->execute("ansible-playbook -i $root_dir/ansible/inventory.yaml $root_dir/ansible/playbooks/publish.yaml");
        }
    }

