<?php
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Класс поля формы Country компонента Regoffices.
 */
class JFormFieldRegofficesCountry extends JFormFieldList{
	/**
	 * Тип поля.
	 *
	 * @var  string
	 */
	protected $type = 'RegofficesCountry';

	/**
	 * Метод для получения списка опций для поля списка.
	 *
	 * @return  array  Массив JHtml опций.
	 */
	protected function getOptions()
	{
		/*$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__exhibitions');
		$db->setQuery($query);
		$messages = $db->loadObjectList();

		// Массив JHtml опций.
		$options = array();

		if ($messages)
		{
			foreach ($messages as $message)
			{
				$options[] = JHtml::_('select.option', $message->id, $message->greeting);
			}
		}*/

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
