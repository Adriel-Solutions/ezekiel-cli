<?php

    namespace commands\ansible;

    use classes\Application;
    use classes\Command;
    use classes\Constants;

    class Setup extends Command {
        protected string $identifier = 'ansible:setup';
        protected string $description = 'Configure Ansible variables for deployment';
        protected string $help = 'Usage: ezekiel ansible:setup';
        protected array $dependencies = [ 'ansible' , 'ansible-playbook' ];

        public function run(Application $app, ?string ...$args): void
        {
            // Prompt every variable
            $settings = [
                'SSH_HOST' => $app->prompt('SSH Host : ', ''),
                'DOMAIN' => $app->prompt('Domain : '),
                'SSL_EMAIL' => $app->prompt('Email for SSL renewals : '),
                'GIT_URL' => $app->prompt('Git repository URL : ')
            ];

            // Retrieve the default template 
            $template = file_get_contents($app->dir_templates() . "/ansible/inventory.yaml");
            foreach($settings as $k => $v)
                $template = str_replace("<$k>", $v, $template);

            file_put_contents($app->dir_root() . "/ansible/inventory.yaml", $template);

            $app->output('Ansible inventory properly configured');
        }
    }
