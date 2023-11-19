<?

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
$this->setFrameMode(true);
\Bitrix\Main\UI\Extension::load("ui.bootstrap4");
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Ноутбук - <?= $arResult['NOTEBOOK']->getName() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-6">Производитель:</div>
        <div class="col-6"><?= $arResult['VENDOR']->getName(); ?></div>
    </div>
    <div class="row">
        <div class="col-6">Модель:</div>
        <div class="col-6"><?= $arResult['MODEL']->getName(); ?></div>
    </div>
    <div class="row">
        <div class="col-6">Наименование:</div>
        <div class="col-6"><?= $arResult['NOTEBOOK']->getName(); ?></div>
    </div>
    <h2>Характеристики:</h2>
    <div class="row">
        <div class="col-6">Год выпуска:</div>
        <div class="col-6"><?= $arResult['NOTEBOOK']->getYear(); ?></div>
    </div>
    <div class="row">
        <div class="col-6">Цена (руб.):</div>
        <div class="col-6"><?= $arResult['NOTEBOOK']->getPrice(); ?></div>
    </div>
    <?
    foreach ($arResult['OPTIONS'] as $option): ?>
        <div class="row">
            <div class="col-6"><?= $option->getOption()->getName() ?>:</div>
            <div class="col-6"><?= $option->getValue() ?></div>
        </div>
    <?
    endforeach; ?>

</div>