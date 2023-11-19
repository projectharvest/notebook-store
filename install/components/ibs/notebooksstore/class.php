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
    ShowError(Loc::getMessage('NOTEBOOKSSTORE_MODULE_NOT_INSTALLED'));
    return;
}

class NotebooksStoreComponent extends CBitrixComponent
{
    const CACHE_TIME = 3600;

    public function executeComponent()
    {
        global $APPLICATION;

        $arDefaultUrlTemplates404 = [
            "detail" => "detail/#NOTEBOOK#/",
            "vendors" => "",
            "models" => "#VENDOR#/",
            "notebooks" => "#VENDOR#/#MODEL#/",

        ];

        $arDefaultVariableAliases404 = [];

        $arDefaultVariableAliases = [
            'VENDOR_ID' => 'VENDOR_ID',
            'VENDOR' => 'VENDOR',
            'MODEL_ID' => 'MODEL_ID',
            'MODEL' => 'MODEL',
            'NOTEBOOK_ID' => 'NOTEBOOK_ID',
            'NOTEBOOK' => 'NOTEBOOK',
        ];


        $arComponentVariables = [
            "VENDOR_ID",
            "VENDOR",
            "MODEL_ID",
            "MODEL",
            "NOTEBOOK_ID",
            "NOTEBOOK",
        ];

        $SEF_FOLDER = '';
        $arUrlTemplates = [];
        if ($this->arParams['SEF_MODE'] == 'Y') {
            $arVariables = [];
            $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates(
                $arDefaultUrlTemplates404,
                $this->arParams['SEF_URL_TEMPLATES']
            );

            $arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
                $arDefaultVariableAliases404,
                $this->arParams['VARIABLE_ALIASES']
            );
            $componentPage = CComponentEngine::ParseComponentPath(
                $this->arParams['SEF_FOLDER'],
                $arUrlTemplates,
                $arVariables
            );
            if (!$componentPage) {
                $requestURL = Bitrix\Main\Context::getCurrent()->getRequest()->getRequestedPage();
                $requestURL = str_replace($this->arParams['SEF_FOLDER'], '', $requestURL);
                if (count(explode('/', $requestURL)) == 1) {
                    $componentPage = 'vendors';
                } else {
                    /**
                     * Сделаем по простому 404
                     */
                    if (!defined("ERROR_404")) {
                        define("ERROR_404", "Y");
                    }
                    \CHTTP::setStatus("404 Not Found");

                    if ($APPLICATION->RestartWorkarea()) {
                        require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
                    }
                }
            }

            CComponentEngine::InitComponentVariables(
                $componentPage,
                $arComponentVariables,
                $arVariableAliases,
                $arVariables
            );

            $SEF_FOLDER = $this->arParams['SEF_FOLDER'];
        } else {
            /**
             * Комплексный компонент должен работать в режиме ЧПУ
             */
            $arVariables = [];
            $arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
                $arDefaultVariableAliases,
                $this->arParams['VARIABLE_ALIASES']
            );

            CComponentEngine::InitComponentVariables(
                false,
                $arComponentVariables,
                $arVariableAliases,
                $arVariables
            );
            $componentPage = '';

            if (intval($arVariables['MODEL_ID']) > 0) {
                $componentPage = 'notebooks';
            } elseif (intval($arVariables['VENDOR_ID']) > 0) {
                $componentPage = 'models';
            } else {
                $componentPage = 'vendors';
            }
        }
        $this->arResult = [
            'FOLDER' => $SEF_FOLDER,
            'URL_TEMPLATES' => $arUrlTemplates,
            'VARIABLES' => $arVariables,
            'ALIASES' => $arVariableAliases,
        ];
        $this->IncludeComponentTemplate($componentPage);
    }
}