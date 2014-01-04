<?php
/**
 * @package     Courses
 * @subpackage  com_courses
 *
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @copyright   Copyright (C) 2014 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Courses helper.
 *
 * @package     Courses
 * @subpackage  com_courses
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class CoursesHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_COURSES_SUBMENU_COURSES'),
			'index.php?option=com_courses&view=courses',
			$vName == 'courses'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_COURSES_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_courses',
			$vName == 'categories'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_COURSES_SUBMENU_FEATURED'),
			'index.php?option=com_courses&view=featured',
			$vName == 'featured'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  $categoryId  The category ID.
	 * @param   integer  $id          The item ID.
	 * @param   string   $assetName   The asset name.
	 *
	 * @return  JObject  A JObject containing the allowed actions.
	 *
	 * @since   3.2
	 */
	public static function getActions($categoryId = 0, $id = 0, $assetName = '')
	{
		// Initialiase variables.
		$user   = JFactory::getUser();
		$result = new JObject;
		$path   = JPATH_ADMINISTRATOR . '/components/' . $assetName . '/access.xml';

		if (empty($id) && empty($categoryId))
		{
			$section = 'component';
		}
		elseif (empty($id))
		{
			$section = 'category';
			$assetName .= '.category.' . (int) $categoryId;
		}
		else
		{
			$section = 'course';
			$assetName .= '.course.' . (int) $id;
		}

		$actions = JAccess::getActionsFromFile($path, "/access/section[@name='" . $section . "']/");

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
