<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку таблиц Joomla.
jimport('joomla.database.table');

/**
 * Класс таблицы Cities.
 */
class ToursTableCities extends JTable
{
	/**
	 * Конструктор.
	 *
	 * @param   JDatabase  &$db  Коннектор объекта базы данных.
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__cities', 'id', $db);
	}
}
