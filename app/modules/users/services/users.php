<?php

    namespace app\modules\users\services;

    use native\facades\Service;

    class Users extends Service {
        protected string $table = "users";
        protected array $relations = [];
        protected array $schema = [];
    }