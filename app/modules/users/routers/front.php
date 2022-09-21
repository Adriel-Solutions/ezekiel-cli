<?php
    namespace app\modules\users\routers;

    use native\libs\Router;

    class Front extends Router {
        protected function load() : void
        {
            $controller = module_ctrl('users' ,'controller');

            $this->get('/route', [$controller, 'handle']);
        }
    }