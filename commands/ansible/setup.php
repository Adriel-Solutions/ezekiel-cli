<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Setup extends Command {
        protected string $identifier = 'ansible:setup';
        protected string $description = 'Configure Ansible variables for deployment';
        protected string $help = 'Usage: ezekiel ansible:setup';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' , 'ansible-galaxy' ];

        public function run(Application $app, ?string ...$args): void
        {
            $this->exit_if_missing_dependencies($app);

            // Prompt every variable
            $settings = [
                'SSH_HOST' => $app->prompt('SSH Host : ', ''),
                'DOMAIN' => $app->prompt('Domain : '),
                'PROJECT_NAME' => $app->prompt('Project name : '),
                'SSL_EMAIL' => $app->prompt('Email for SSL renewals : '),
                'GIT_URL' => $app->prompt('Git repository URL : '),
            ];

            // Retrieve the default template 
            $output = $app->fill_template("/ansible/inventory.yaml", $settings);

            file_put_contents($app->dir_root() . "/ansible/inventory.yaml", $output);

            $app->execute('cp ./configuration/.custom.env ./configuration/.production.env');

            $app->output('Ansible inventory properly configured');

            passthru('ansible-galaxy install -r ./ansible/requirements.yml');

            $app->output('Ansible dependencies installed');
        }
    }
