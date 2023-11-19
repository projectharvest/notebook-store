<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Iblock;

$arComponentParameters = [
    "GROUPS" => [
        "PARAMS" => [
            "NAME" => GetMessage('PARAMS'),
        ],
    ],
    "PARAMETERS" => [],
];
?>
