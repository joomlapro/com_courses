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

// Load the tabstate behavior script.
JHtml::_('behavior.tabstate');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_courses'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register dependent classes.
JLoader::register('CoursesHelper', __DIR__ . '/helpers/courses.php');
JLoader::register('LessonsHelper', __DIR__ . '/helpers/lessons.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Courses');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
