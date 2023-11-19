<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentDescription = [
    "NAME" => GetMessage('COMPONENT_NAME'),
    "DESCRIPTION" => GetMessage('COMPONENT_DESCRIPTION'),
    "ICON" => "/images/cat_list.gif",
    "CACHE_PATH" => "Y",
    "COMPLEX" => "Y",
    "SORT" => 1,
    "PATH" => [
        "ID" => "IBS",
        "NAME" => "IBS",
        "CHILD" => [
            "ID" => "NOTEBOOKSSTORE",
            "SORT" => 10,
            "CHILD" => [
                "ID" => "STORE",
                "NAME" => GetMessage('COMPONENT_SUB_NAME'),
            ],
        ],
    ],
];

?>