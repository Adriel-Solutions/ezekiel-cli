<?php

    namespace app\modules\<LOWER_NAME>;

    use native\libs\Router;
    use native\libs\Hooks;
    use native\libs\Module as BaseModule;

    /**
     * @module <NAME>
     */
    class Module extends BaseModule {
        public function activate() : void 
        {
            Hooks::register('before_mount_front', function(Router $router) {
                $router->mount(module_router('<LOWER_NAME>', 'front'));
            });

            Hooks::register('before_mount_api', function(Router $router) {
                $router->mount(module_router('<LOWER_NAME>', 'api'));
            });

            Hooks::register('before_mount_webhooks', function(Router $router) {
                $router->mount(module_router('<LOWER_NAME>', 'webhooks'));
            });
        }
    }

