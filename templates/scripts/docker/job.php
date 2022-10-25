<?php
    require './native/index.php';
    $service = default_service('jobs');
    $job = $service->get(<ID>);
    print(json_encode($job));
