<?php
require dirname(__FILE__) . '/../config.php';

define('PATH_SRC', realpath(dirname(__FILE__). '/src') );
set_include_path(get_include_path()
    // base and abstract classes of this app
    . PATH_SEPARATOR . PATH_SRC . '/app/'
    . PATH_SEPARATOR . PATH_SRC . '/out/Auto/Enums'
    . PATH_SEPARATOR . PATH_SRC . '/out/Auto/Services'
    . PATH_SEPARATOR . PATH_SRC . '/out/Auto/Structures'
    . PATH_SEPARATOR . PATH_SRC . '/out/Auto/Structures/Proto'
    . PATH_SEPARATOR . PATH_SRC . '/out/Enums'
    . PATH_SEPARATOR . PATH_SRC . '/out/Services'
    . PATH_SEPARATOR . PATH_SRC . '/out/Structures'
    . PATH_SEPARATOR . PATH_SRC . '/out/Structures/Proto'
);

MyApp::me()->run();
