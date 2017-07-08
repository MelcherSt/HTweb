<?php

\Autoloader::add_core_namespace('Api', __DIR__.'/classes');

Autoloader::add_classes(array(
    'Api\\Controller_Auth' => __DIR__.'/classes/controller/auth.php',
    'Api\\Controller_Paginated' => __DIR__.'/classes/controller/paginated.php',
    'Api\\Response_Base' => __DIR__.'/classes/response/base.php',
    'Api\\Response_Paginated' => __DIR__.'/classes/response/paginated.php',
    'Api\\Response_Status' => __DIR__.'/classes/response/status.php',
));
