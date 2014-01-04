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
 * Lesson JSON controller for Courses Component.
 *
 * @package     Courses
 * @subpackage  com_courses
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class CoursesControllerLesson extends JControllerLegacy
{
	/**
	 * Method to delete lesson using ajax.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function deleteLessonAjax()
	{
		// Check for request forgeries.
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$cid = $this->input->get('cid', array(), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			$response = array(
				'status' => '0',
				'error' => JText::_('COM_COURSES_NO_LESSONS_SELECTED')
			);
		}
		else
		{
			// Get the model.
			$model = $this->getModel('Lesson', 'CoursesModel');

			// Make sure the item ids are integers.
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if (!$model->delete($cid))
			{
				$response = array(
					'status' => '0',
					'error' => $model->getError()
				);
			}
			else
			{
				$response = array(
					'status' => '1',
					'error' => JText::plural('COM_COURSES_N_lessons_DELETED', count($cid))
				);
			}
		}

		// Send the JSON response.
		echo json_encode($response);

		// Close the application.
		JFactory::getApplication()->close();
	}
}
