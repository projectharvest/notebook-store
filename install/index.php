<?
/**
 * User: Kirill Platonov
 */

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\EventManager;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;

if (class_exists('ibs_notebooksstore')) {
    return;
}

class ibs_notebooksstore extends CModule
{
    var $MODULE_ID = "ibs.notebooksstore";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function __construct()
    {
        $arModuleVersion = [];
        include dirname(__FILE__) . '/version.php';
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME = $arModuleVersion["MODULE_NAME"];
            $this->MODULE_DESCRIPTION = $arModuleVersion["MODULE_DESCRIPTION"];
        }
        $this->PARTNER_NAME = 'ibs';
        $this->PARTNER_URI = 'https://ibs.ru';
    }

    function GetModuleRightList()
    {
        return [
            "reference_id" => ["D", "R", "W"],
            "reference" => [
                '[D] ' . Loc::getMessage("IBS_NS_RIGHT_DENIED"),
                '[R] ' . Loc::getMessage("IBS_NS_RIGHT_READ"),
                '[W] ' . Loc::getMessage("IBS_NS_RIGHT_FULL"),
            ],
        ];
    }


    function InstallEvent()
    {
    }

    function InstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();
            if (!$connection->isTableExists(Ibs\NotebooksStore\Tables\VendorsTable::getTableName())) {
                Ibs\NotebooksStore\Tables\VendorsTable::getEntity()->createDbTable();
            }
            if (!$connection->isTableExists(Ibs\NotebooksStore\Tables\ModelsTable::getTableName())) {
                Ibs\NotebooksStore\Tables\ModelsTable::getEntity()->createDbTable();
            }
            if (!$connection->isTableExists(Ibs\NotebooksStore\Tables\NotebooksTable::getTableName())) {
                Ibs\NotebooksStore\Tables\NotebooksTable::getEntity()->createDbTable();
            }
            if (!$connection->isTableExists(Ibs\NotebooksStore\Tables\OptionsTable::getTableName())) {
                Ibs\NotebooksStore\Tables\OptionsTable::getEntity()->createDbTable();
            }
            if (!$connection->isTableExists(Ibs\NotebooksStore\Tables\NotebookOptionTable::getTableName())) {
                Ibs\NotebooksStore\Tables\NotebookOptionTable::getEntity()->createDbTable();
            }
            $this->AddDemoData();
            return true;
        }
    }

    public function AddDemoData()
    {
        $brands = [
            'Aser',
            'ASUS',
            'Apple',
            'Lenovo',
            'HONOR',
        ];
        $vendors = [];
        foreach ($brands as $brand) {
            $result = Ibs\NotebooksStore\Tables\VendorsTable::add(
                [
                    'NAME' => $brand,
                ]
            );
            if ($result->isSuccess()) {
                $vendors[$result->getId()] = $brand;
            }
        }
        $models = [];
        foreach ($vendors as $number => $vendorName) {
            for ($i = 1; $i < rand(2, 5); $i++) {
                $result = Ibs\NotebooksStore\Tables\ModelsTable::add(
                    [
                        'NAME' => "Модель-" . $number . '-' . $i,
                        'VENDOR_ID' => $number,
                    ]
                );
                if ($result->isSuccess()) {
                    $models[$result->getId()] = $result->getData();
                }
            }
        }
        foreach ($models as $number => $modelName) {
            for ($i = 1; $i < rand(2, 5); $i++) {
                $result = Ibs\NotebooksStore\Tables\NotebooksTable::add(
                    [
                        'NAME' => "Ноутбук-" . $number . '-' . $i,
                        'YEAR' => rand(1970, 2023),
                        'PRICE' => rand(
                                30000,
                                120000
                            ) + (rand(0, 10) / 10),
                        'MODEL_ID' => $number,
                    ]
                );
                if ($result->isSuccess()) {
                    $notebooks[$result->getId()] = $result->getData();
                }
            }
        }

        $properties = [
            'Вес',
            'Размер',
            'Цвет',
        ];
        $colors = [
            'Белый',
            'Черный',
            'Серый',
            'Красный',
        ];
        $options = [];
        foreach ($properties as $property) {
            $result = Ibs\NotebooksStore\Tables\OptionsTable::add(
                [
                    'NAME' => $property,
                ]
            );
            if ($result->isSuccess()) {
                $options[$result->getId()] = $property;
            }
        }

        foreach ($notebooks as $notebookId => $notebook) {
            foreach ($options as $optionId => $option) {
                switch ($option) {
                    case 'Вес':
                        $value = (rand(1, 3) + rand(1, 100) / 100) . ' кг.';
                        break;
                    case 'Размер':
                        $value = rand(13, 21) . '\'\'';
                        break;
                    case 'Цвет':
                        $value = $colors[array_rand($colors)];
                        break;
                }
                $result = Ibs\NotebooksStore\Tables\NotebookOptionTable::add(
                    [
                        'NOTEBOOK_ID' => $notebookId,
                        'OPTION_ID' => $optionId,
                        'VALUE' => $value,
                    ]
                );
                if ($result->isSuccess()) {
                    //
                }
            }
        }
    }


    function UnInstallEvent()
    {
    }

    function UnInstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();

            if ($connection->isTableExists(Ibs\NotebooksStore\Tables\VendorsTable::getTableName())) {
                $connection->dropTable(Ibs\NotebooksStore\Tables\VendorsTable::getTableName());
            }
            if ($connection->isTableExists(Ibs\NotebooksStore\Tables\ModelsTable::getTableName())) {
                $connection->dropTable(Ibs\NotebooksStore\Tables\ModelsTable::getTableName());
            }
            if ($connection->isTableExists(Ibs\NotebooksStore\Tables\NotebooksTable::getTableName())) {
                $connection->dropTable(Ibs\NotebooksStore\Tables\NotebooksTable::getTableName());
            }
            if ($connection->isTableExists(Ibs\NotebooksStore\Tables\OptionsTable::getTableName())) {
                $connection->dropTable(Ibs\NotebooksStore\Tables\OptionsTable::getTableName());
            }
            if ($connection->isTableExists(Ibs\NotebooksStore\Tables\NotebookOptionTable::getTableName())) {
                $connection->dropTable(Ibs\NotebooksStore\Tables\NotebookOptionTable::getTableName());
            }
        }
    }

    function InstallFiles()
    {
        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        $request = Application::getInstance()->getContext()->getRequest();
        $step = intval($request['step']);
        $reset = (bool)$request['reset'];
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("IBS_NS_INSTALL_TITLE"),
                Application::getDocumentRoot() . "/local/modules/" . $this->MODULE_ID . "/install/step1.php"
            );
        } elseif ($step == 2) {
            if (!IsModuleInstalled($this->MODULE_ID)) {
                ModuleManager::registerModule($this->MODULE_ID);
                if ($reset) {
                    $this->UnInstallDB();
                }
                $this->InstallDB();
                $this->InstallEvent();
                $this->InstallFiles();
                $APPLICATION->IncludeAdminFile(
                    Loc::getMessage("IBS_NS_INSTALL_TITLE"),
                    Application::getDocumentRoot() . "/local/modules/" . $this->MODULE_ID . "/install/step2.php"
                );
            }
        }
    }

    function DoUninstall()
    {
        global $APPLICATION;
        $request = Application::getInstance()->getContext()->getRequest();
        $step = intval($request['step']);
        $saveTables = (bool)$request['save'];
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("IBS_NS_INSTALL_TITLE"),
                Application::getDocumentRoot() . "/local/modules/" . $this->MODULE_ID . "/install/unstep1.php"
            );
        } elseif ($step == 2) {
            $this->UnInstallEvent();
            if (!$saveTables) {
                $this->UnInstallDB();
            }
            $this->UnInstallFiles();
            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("IBS_NS_UNINSTALL_TITLE"),
                Application::getDocumentRoot() . "/local/modules/" . $this->MODULE_ID . "/install/unstep2.php"
            );
        }
    }
}

?>