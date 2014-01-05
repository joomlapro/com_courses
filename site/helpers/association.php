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

// Register dependent classes.
JLoader::register('CoursesHelper', JPATH_ADMINISTRATOR . '/components/com_courses/helpers/courses.php');
JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

/**
 * Courses Component Association Helper.
 *
 * @package     Courses
 * @subpackage  com_courses
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
abstract class CoursesHelperAssociation extends CategoryHelperAssociation
{
	/**
	 * Method to get the associations for a given item.
	 *
	 * @param   integer  $id    Id of the item.
	 * @param   string   $view  Name of the view.
	 *
	 * @return  array  Array of associations for the item.
	 *
	 * @since   3.2
	 */
	public static function getAssociations($id = 0, $view = null)
	{
		// Load route helper.
		jimport('helper.route', JPATH_COMPONENT_SITE);

		// Initialiase variables.
		$app  = JFactory::getApplication();
		$view = is_null($view) ? $app->input->get('view') : $view;
		$id   = empty($id) ? $app->input->getInt('id') : $id;

		if ($view == 'course')
		{
			if ($id)
			{
				$associations = JLanguageAssociations::getAssociations('com_courses', '#__courses', 'com_courses.item', $id);
				$return       = array();

				foreach ($associations as $tag => $item)
				{
					$return[$tag] = CoursesHelperRoute::getCourseRoute($item->id, $item->catid, $item->language);
				}

				return $return;
			}
		}

		if ($view == 'category' || $view == 'categories')
		{
			return self::getCategoryAssociations($id, 'com_courses');
		}

		return array();
	}
}
