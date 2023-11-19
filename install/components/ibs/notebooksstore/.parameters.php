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
    "PARAMETERS" => [
        "SEF_MODE" => [
            "detail" => [
                "NAME" => GetMessage("NOTEBOOK_DETAIL"),
                "DEFAULT" => "detail/#NOTEBOOK#/",
                "VARIABLES" => [
                    "NOTEBOOK",
                    "NOTEBOOK_ID",
                ],
            ],
            "vendors" => [
                "NAME" => GetMessage("VENDORS_LIST"),
                "DEFAULT" => "",
                "VARIABLES" => [
                ],
            ],
            "models" => [
                "NAME" => GetMessage("MODELS_LIST"),
                "DEFAULT" => "#VENDOR#/",
                "VARIABLES" => [
                    "VENDOR_ID",
                    "VENDOR",
                ],
            ],
            "notebooks" => [
                "NAME" => GetMessage("NOTEBOOKS_LIST"),
                "DEFAULT" => "#VENDOR#/#MODEL#/",
                "VARIABLES" => [
                    "MODEL_ID",
                    "MODEL",
                    "VENDOR_ID",
                    "VENDOR",
                ],
            ],
        ],

    ],
];
?>
