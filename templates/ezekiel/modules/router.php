<?php
    namespace app\modules\<LOWER_NAME>\routers;

    use native\libs\Router;

    class <ROUTER_NAME> extends Router {
        protected function load() : void
        {
            $controller = module_ctrl('<LOWER_NAME>' ,'<CONTROLLER_NAME>');

            $this->get('/route', [$controller, 'handle']);
        }
    }

