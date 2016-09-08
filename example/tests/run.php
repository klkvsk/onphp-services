<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-25
 */

require dirname(__FILE__) . '/config.php';

define('PATH_SRC', realpath(dirname(__FILE__). '/out/') );
set_include_path(get_include_path()
    . PATH_SEPARATOR . PATH_SRC . '/Auto/Enums'
    . PATH_SEPARATOR . PATH_SRC . '/Auto/Services'
    . PATH_SEPARATOR . PATH_SRC . '/Auto/Structures'
    . PATH_SEPARATOR . PATH_SRC . '/Auto/Structures/Proto'
    . PATH_SEPARATOR . PATH_SRC . '/Enums'
    . PATH_SEPARATOR . PATH_SRC . '/Services'
    . PATH_SEPARATOR . PATH_SRC . '/Structures'
    . PATH_SEPARATOR . PATH_SRC . '/Structures/Proto'
);

$cases = glob(dirname(__FILE__) . '/cases/*.php');
foreach ($cases as $case) {
    include $case;
}