<?php
/**
 * User: Kirill Platonov
 * Date: 14/11/23
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
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    CUtil;

Loc::loadMessages(__FILE__);

/**
 * Class ****
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string optional
 * <li> CODE string optional
 * </ul>
 *
 * @package Ibs\NotebooksStore\Tables
 **/
class VendorsTable extends Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ibs_ns_vendors';
    }

    /**
     * @param Main\Entity\Event $event
     * @return Main\Entity\EventResult
     */
    public static function onBeforeAdd(Main\Entity\Event $event)
    {
        $result = new Main\Entity\EventResult;
        $data = $event->getParameter("fields");
        if (isset($data['CODE'])) {
            $cleanCode = preg_replace("/[^a-zA-Z0-9\s]/", '', $data['CODE']);
            $result->modifyFields(['CODE' => $cleanCode]);
        } else {
            $result->modifyFields(['CODE' => CUtil::translit($data['NAME'], 'ru')]);
        }
        return $result;
    }

    /**
     * @param Main\Entity\Event $event
     * @return Main\Entity\EventResult
     */
    public static function onBeforeUpdate(Main\Entity\Event $event)
    {
        $result = new Main\Entity\EventResult;
        $data = $event->getParameter("fields");
        if (isset($data['CODE'])) {
            $cleanCode = preg_replace("/[^a-zA-Z0-9\s]/", '', $data['CODE']);
            if (empty($cleanCode)) {
                $result->modifyFields(['CODE' => CUtil::translit($data['NAME'], 'ru')]);
            } else {
                $result->modifyFields(['CODE' => $cleanCode]);
            }
        }
        return $result;
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('VENDORS_ENTITY_ID_FIELD'),
                ]
            ),
            new TextField(
                'NAME',
                [
                    'required' => true,
                    'title' => Loc::getMessage('VENDORS_ENTITY_NAME_FIELD'),
                ]
            ),
            (new TextField(
                'CODE',
                [
                    'title' => Loc::getMessage('MODELS_ENTITY_CODE_FIELD'),
                    'validation' => function () {
                        return [
                            new \Bitrix\Main\ORM\Fields\Validators\UniqueValidator(Loc::getMessage("DUPLICATED_ERROR")),
                        ];
                    },
                ]
            )),
            (new OneToMany(
                'MODELS',
                ModelsTable::class,
                'VENDOR'
            ))->configureJoinType('inner'),
        ];
    }
}
