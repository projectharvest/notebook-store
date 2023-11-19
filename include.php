<?php
/**
 * User: Kirill Platonov
 * Date: 14/11/23
 * Time: 21:00
 */

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

Loader::registerAutoLoadClasses(
    'ibs.notebooksstore',
    [
        'Ibs\NotebooksStore\Table\Vendors' => 'lib/tables/vendorstable.php',
        'Ibs\NotebooksStore\Table\Models' => 'lib/tables/modelstable.php',
        'Ibs\NotebooksStore\Table\Notebooks' => 'lib/tables/notebookstable.php',
        'Ibs\NotebooksStore\Table\Options' => 'lib/tables/optionstable.php',
        'Ibs\NotebooksStore\Table\NotebookOptionTable' => 'lib/tables/notebookoptiontable.php',
    ]
);
