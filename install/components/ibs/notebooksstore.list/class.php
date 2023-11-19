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

class NotebooksStoreListComponent extends CBitrixComponent
{
    const CACHE_TIME = 300;

    public function executeComponent()
    {
        global $APPLICATION;
        $arParams = &$this->arParams;
        $arResult = &$this->arResult;

        $APPLICATION->AddChainItem('Производители', $this->arParams['FOLDER']);
        $arResult['COLUMNS'] = [];
        $arResult['COLUMNS'][] = ['id' => 'ID', 'name' => 'ID', 'sort' => 'ID', 'default' => true];
        $arResult['COLUMNS'][] = ['id' => 'NAME', 'name' => 'Название', 'sort' => 'NAME', 'default' => true];
        if (isset($arParams['VARIABLES']['MODEL'])) {
            if (!($model = $this->getObjectByCode(
                \Ibs\NotebooksStore\Tables\ModelsTable::getEntity(),
                $arParams['VARIABLES']['MODEL']
            ))) {
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
            $queryList = new Main\Entity\Query(\Ibs\NotebooksStore\Tables\NotebooksTable::getEntity());
            $queryList->setSelect(['ID', 'NAME', 'CODE', 'YEAR', 'PRICE']);
            $queryList->setFilter(['MODEL_ID' => $model->getId()]);
            $arResult['TITLE'] = 'Список ноутбуков модели - ' . $model->getName();
            $model->fillVendor();
            $arResult['RETURN'] = $this->arParams['FOLDER'] . $model->getVendor()->getCode() . '/';
            $APPLICATION->AddChainItem($model->getVendor()->getName(), $arResult['RETURN']);
            $APPLICATION->AddChainItem($model->getName(), $arResult['RETURN'] . $model->getCode() . '/');
            $arResult['GRID_ID'] = 'notebooks_grid';
            $arResult['COLUMNS'][] = [
                'id' => 'YEAR',
                'name' => 'Год производства',
                'sort' => 'YEAR',
                'default' => true,
            ];
            $arResult['COLUMNS'][] = ['id' => 'PRICE', 'name' => 'Ценa', 'sort' => 'PRICE', 'default' => true];
        } elseif (empty($model) and isset($arParams['VARIABLES']['VENDOR'])) {
            if (!($vendor = $this->getObjectByCode(
                \Ibs\NotebooksStore\Tables\VendorsTable::getEntity(),
                $arParams['VARIABLES']['VENDOR']
            ))) {
                if (!defined("ERROR_404")) {
                    define("ERROR_404", "Y");
                }
                \CHTTP::setStatus("404 Not Found");

                if ($APPLICATION->RestartWorkarea()) {
                    require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
                }
            }
            $queryList = new Main\Entity\Query(\Ibs\NotebooksStore\Tables\ModelsTable::getEntity());
            $queryList->setSelect(['ID', 'NAME', 'CODE']);
            $queryList->setFilter(['VENDOR_ID' => $vendor->getId()]);
            $arResult['TITLE'] = 'Список моделей производителя - ' . $vendor->getName();
            $arResult['RETURN'] = $this->arParams['FOLDER'];
            $APPLICATION->AddChainItem($vendor->getName(), $arResult['RETURN'] . $vendor->getCode() . '/');
            $arResult['GRID_ID'] = 'models_grid';
        } else {
            $queryList = new Main\Entity\Query(\Ibs\NotebooksStore\Tables\VendorsTable::getEntity());
            $queryList->setSelect(['ID', 'NAME', 'CODE']);
            $arResult['TITLE'] = 'Список производителей';
            $arResult['GRID_ID'] = 'vendors_grid';
        }

        $grid_options = new Bitrix\Main\Grid\Options($arResult['GRID_ID']);
        $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $nav_params = $grid_options->GetNavParams();

        $arResult['NAV'] = new Bitrix\Main\UI\PageNavigation($arResult['GRID_ID']);
        $arResult['NAV']->allowAllRecords(false)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $queryList->setLimit((($limit = $arResult['NAV']->getLimit()) > 0 ? $limit + 1 : 0));
        $queryList->setOffset($offset = $arResult['NAV']->getOffset());
        $queryList->setOrder($sort['sort']);

        $n = 0;
        foreach ($queryList->exec()->fetchCollection()->getAll() as $item) {
            $n++;
            if ($limit > 0 and count($arResult['ITEMS']) == $limit) {
                break;
            }
            $data = [];
            foreach ($arResult['COLUMNS'] as $column) {
                $data[$column['id']] = $item->get($column['id']);
            }

            $arResult['ITEMS'][] = [
                'data' => $data,
                'actions' => [
                    [
                        'text' => 'Просмотр',
                        'default' => true,
                        'onclick' => 'document.location.href="' . ($model ? $arParams['FOLDER'] . 'detail/' . $item->getCode(
                                ) : $item->getCode()) . '/"',
                    ],
                ],
            ];
        }
        $arResult['NAV']->setRecordCount($offset + $n);
        $this->IncludeComponentTemplate();
    }

    /**
     * @param $entity
     * @param $value
     * @return mixed
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     * @throws Main\SystemException
     */
    public function getObjectByCode($entity, $value)
    {
        $query = new Main\Entity\Query($entity);
        $query->setSelect(['*']);
        $query->setFilter(['=CODE' => $value]);
        return $query->exec()->fetchObject();
    }

}