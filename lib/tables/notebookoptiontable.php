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
    Bitrix\Main\ORM\Fields\Relations\ManyToMany,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\ScalarField,
    Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class ****
 *
 * Fields:
 * <ul>
 * <li> NOTEBOOK_ID int mandatory
 * <li> OPTION_ID int mandatory
 * </ul>
 *
 * @package Ibs\NotebooksStore\Tables
 **/
class NotebookOptionTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_ns_notebook_option';
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
                'NOTEBOOK_ID',
                [
                    'primary' => true,
                    'title' => Loc::getMessage('NOTEBOOK_OPTION_ENTITY_NOTEBOOK_ID_FIELD'),
                ]
            )),
            (new Reference(
                'NOTEBOOK',
                NotebooksTable::class,
                Join::on('this.NOTEBOOK_ID', 'ref.ID')
            )),
            (new IntegerField(
                'OPTION_ID',
                [
                    'primary' => true,
                    'title' => Loc::getMessage('NOTEBOOK_OPTION_ENTITY_OPTION_ID_FIELD'),
                ]
            )),
            (new Reference(
                'OPTION',
                OptionsTable::class,
                Join::on('this.OPTION_ID', 'ref.ID')
            )),
            (new TextField('VALUE'))
                ->configureDefaultValue(''),
        ];
    }
}
