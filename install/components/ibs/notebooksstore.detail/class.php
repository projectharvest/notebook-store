<?php

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\Type\DateTime;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock;
use \Bitrix\Iblock\Component\ElementList;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

if (!\Bitrix\Main\Loader::includeModule('ibs.notebooksstore')) {
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}

class NotebooksStoreDetailComponent extends CBitrixComponent
{
    const CACHE_TIME = 3600;

    public function executeComponent()
    {
        global $APPLICATION;
        $arParams = &$this->arParams;
        $arResult = &$this->arResult;

        if ($this->StartResultCache(self::CACHE_TIME)) {
            if (isset($arParams['VARIABLES']['NOTEBOOK'])) {
                $query = new Main\Entity\Query(\Ibs\NotebooksStore\Tables\NotebooksTable::getEntity());
                $query->setSelect(['*', 'OPTION_ITEMS', 'MODEL']);
                $query->setFilter(['=CODE' => $arParams['VARIABLES']['NOTEBOOK']]);
                $notebook = $query->exec()->fetchObject();

                if (!$notebook) {
                    /**
                     * По умолчанию при не валидной ссылке указываем 404 ошибку,
                     * можно показывать уровень выше, но тогда будут проблемы CEO-оптимизации
                     */
                    if (!defined("ERROR_404")) {
                        define("ERROR_404", "Y");
                    }
                    \CHTTP::setStatus("404 Not Found");

                    if ($APPLICATION->RestartWorkarea()) {
                        require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
                    }
                }
                $arResult['MODEL'] = $notebook->getModel();
                $arResult['MODEL']->fillVendor();
                $arResult['VENDOR'] = $arResult['MODEL']->getVendor();
                $arResult['NOTEBOOK'] = $notebook;
                foreach ($notebook->getOptionItems() as $optionItem) {
                    $optionItem->fillOption();
                    $arResult['OPTIONS'][] = $optionItem;
                }

                $link = $this->arParams['FOLDER'];
                $APPLICATION->AddChainItem('Производители', $link);
                $APPLICATION->AddChainItem(
                    $arResult['VENDOR']->getName(),
                    $link = $link . $arResult['VENDOR']->getCode() . '/'
                );
                $APPLICATION->AddChainItem(
                    $arResult['MODEL']->getName(),
                    $link = $link . $arResult['MODEL']->getCode() . '/'
                );
                $APPLICATION->AddChainItem(
                    $arResult['NOTEBOOK']->getName(),
                    $link = $link . $arResult['NOTEBOOK']->getCode() . '/'
                );
            }
            $this->IncludeComponentTemplate();
        }
    }
}