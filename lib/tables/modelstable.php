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
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

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
class ModelsTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_ns_models';
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
                    'title' => Loc::getMessage('MODELS_ENTITY_ID_FIELD'),
                ]
            )),
            (new TextField(
                'NAME',
                [
                    'required' => true,
                    'title' => Loc::getMessage('MODELS_ENTITY_NAME_FIELD'),
                ]
            )),
            (new IntegerField('VENDOR_ID')),
            (new Reference(
                'VENDOR',
                VendorsTable::class,
                Join::on('this.VENDOR_ID', 'ref.ID')
            ))->configureJoinType('inner'),
            (new OneToMany(
                'NOTEBOOKS',
                NotebooksTable::class,
                'MODEL'
            ))->configureJoinType('inner'),

        ];
    }
}
