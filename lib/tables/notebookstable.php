<?php
/**
 * User: Kirill Platonov
 * Date: 07/06/20
 * Time: 21:00
 */

namespace Ibs\NotebooksStore\Tables;

use Bitrix\Main,
	Bitrix\Main\Entity,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\DatetimeField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\TextField,
	Bitrix\Main\ORM\Fields\BooleanField,
	Bitrix\Main\ORM\Fields\FloatField,
	Bitrix\Main\ORM\Fields\Relations\Reference,
	Bitrix\Main\ORM\Fields\Relations\ManyToMany,
	Bitrix\Main\ORM\Query\Join;


Loc::loadMessages(__FILE__);

/**
 * Class ****
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string optional
 * <li> YEAR int optional
 * <li> PRICE float optional
 * </ul>
 *
 * @package Ibs\NotebooksStore\Tables
 **/
class NotebooksTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'ibs_ns_notebooks';
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
					'title' => Loc::getMessage('NOTEBOOKS_ENTITY_ID_FIELD'),
				]
			),
			new TextField(
				'NAME',
				[
					'title' => Loc::getMessage('NOTEBOOKS_ENTITY_NAME_FIELD'),
				]
			),
			new IntegerField(
				'YEAR',
				[
					'title' => Loc::getMessage('NOTEBOOKS_ENTITY_YEAR_FIELD'),
					'validation' => function () {
						return [new Entity\Validator\Length(4, 4)];
					},
				]
			),
			new FloatField(
				'PRICE',
				[
					'title' => Loc::getMessage('VENDORS_ENTITY_PRICE_FIELD'),
				]
			),
			(new IntegerField('MODEL_ID')),
			(new Reference(
				'MODEL',
				ModelsTable::class,
				Join::on('this.MODEL_ID', 'ref.ID')
			))->configureJoinType('inner'),
			(new ManyToMany('OPTIONS', OptionsTable::class))
				->configureTableName('ibs_ns_notebook_option'),

		];
	}
}
