<?

use Bitrix\Main\Grid\Panel\Actions;
use Bitrix\Main\Grid\Panel\Snippet\Onchange;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);
\Bitrix\Main\UI\Extension::load("ui.bootstrap4");
?>

<div class="container text-center">
    <div class="row">
        <div class="col">
            <h1><?= $arResult['TITLE'] ?></h1>
        </div>
        <div class="row">
            <div class="col">
                <?
                if (isset($arResult['RETURN'])) : ?>
                    <div class="text-left text-info">
                        <a href="<?= $arResult['RETURN'] ?>">Назад</a>
                    </div>
                <?
                endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:main.ui.grid',
                    '',
                    [
                        'GRID_ID' => $arResult['GRID_ID'],
                        'COLUMNS' => $arResult['COLUMNS'],
                        'ROWS' => $arResult['ITEMS'],
                        'SHOW_ROW_CHECKBOXES' => false,
                        'NAV_OBJECT' => $arResult['NAV'],
                        'AJAX_MODE' => 'Y',
                        'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
                        'PAGE_SIZES' => [
                            ['NAME' => "5", 'VALUE' => '5'],
                            ['NAME' => '10', 'VALUE' => '10'],
                            ['NAME' => '20', 'VALUE' => '20'],
                            ['NAME' => '50', 'VALUE' => '50'],
                            ['NAME' => '100', 'VALUE' => '100'],
                        ],

                        'AJAX_OPTION_JUMP' => 'N',
                        'SHOW_CHECK_ALL_CHECKBOXES' => false,
                        'SHOW_ROW_ACTIONS_MENU' => true,
                        'SHOW_GRID_SETTINGS_MENU' => true,
                        'SHOW_NAVIGATION_PANEL' => true,
                        'SHOW_PAGINATION' => true,
                        'SHOW_SELECTED_COUNTER' => true,
                        'SHOW_TOTAL_COUNTER' => true,
                        'SHOW_PAGESIZE' => true,
                        'SHOW_ACTION_PANEL' => true,
                        'ACTION_PANEL' => [],
                        'ALLOW_COLUMNS_SORT' => true,
                        'ALLOW_COLUMNS_RESIZE' => true,
                        'ALLOW_HORIZONTAL_SCROLL' => true,
                        'ALLOW_SORT' => true,
                        'ALLOW_PIN_HEADER' => true,
                        'AJAX_OPTION_HISTORY' => 'N',

                    ]
                );
                ?>
            </div>
        </div>
    </div>
