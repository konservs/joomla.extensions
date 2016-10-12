<?php
// ������ ������� �������.
defined('_JEXEC') or die;
 
/**
 * ������ HelloWorld ����������.
 */
abstract class ToursHelper{
	/**
	 * ������������� �������.
	 *
	 * @param   string  $submenu  �������� ����� ����.
	 *
	 * @return  void
	 */
	public static function addSubmenu($submenu){
		//Tours list
		JSubMenuHelper::addEntry(
			JText::_('COM_TOURS_TOURS'),
			'index.php?option=com_tours',
			$submenu == 'tours'
        		);
		//Categories list
		JSubMenuHelper::addEntry(
			JText::_('COM_TOURS_CATEGORIES'),
			'index.php?option=com_tours&view=categories',
			$submenu == 'categories'
			); 
		//Countries list
		JSubMenuHelper::addEntry(
			JText::_('COM_TOURS_COUNTRIES'),
			'index.php?option=com_tours&view=countries',
			$submenu == 'countries'
			); 
		//Cities list
		JSubMenuHelper::addEntry(
			JText::_('COM_TOURS_CITIES'),
			'index.php?option=com_tours&view=cities',
			$submenu == 'cities'
			);
		//Orders list
		JSubMenuHelper::addEntry(
			JText::_('COM_TOURS_ORDERS'),
			'index.php?option=com_tours&view=orders',
			$submenu == 'orders'
			); 
		//������������� ���������� ��������.
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-helloworld ' .
			'{background-image: url(../media/com_helloworld/images/hello-48x48.png);}');
    		}
	}
