<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Подключаем библиотеку таблиц Joomla.
jimport('joomla.database.table');

/**
 * Класс таблицы Exhibitions.
 */
class ToursTableIndustries extends JTable
{
	/**
	 * Конструктор.
	 *
	 * @param   JDatabase  &$db  Коннектор объекта базы данных.
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__industries', 'id', $db);
	}
}
