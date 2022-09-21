<?php
    namespace app\modules\users\routers;

    use native\libs\Router;

    class Api extends Router {
        protected function load() : void
        {
            $controller = module_ctrl('users' ,'controller');

            $this->get('/route', [$controller, 'handle']);
        }
    }