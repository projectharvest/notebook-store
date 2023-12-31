<?php
/**
 * User: Kirill Platonov
 * Date: 07/06/20
 * Time: 21:00
 */

namespace Ibs\NotebooksStore\Tables;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DatetimeField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\TextField,
    Bitrix\Main\ORM\Fields\BooleanField,
    Bitrix\Main\ORM\Fields\Relations\OneToMany;

Loc::loadMessages(__FILE__);

/**
 * Class ****
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string optional
 * </ul>
 *
 * @package Ibs\NotebooksStore\Tables
 **/
class OptionsTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_ns_options';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            (new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('OPTIONS_ENTITY_ID_FIELD'),
                ]
            )),
            (new TextField(
                'NAME',
                [
                    'required' => true,
                    'title' => Loc::getMessage('OPTIONS_ENTITY_NAME_FIELD'),
                ]
            )),
            (new oneToMany('NOTEBOOK_ITEMS', NotebookOptionTable::class, 'OPTION')),
        ];
    }
}
