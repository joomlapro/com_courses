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
 * Courses Component Controller.
 *
 * @package     Courses
 * @subpackage  com_courses
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class CoursesController extends JControllerLegacy
{
	/**
	 * The default view.
	 *
	 * @var     string
	 * @since   3.2
	 */
	protected $default_view = 'courses';

	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  This object to support chaining.
	 *
	 * @since   3.2
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Set the default view name and format from the Request.
		$view   = $this->input->get('view', $this->default_view);
		$layout = $this->input->get('layout', 'default', 'string');
		$id     = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'course' && $layout == 'edit' && !$this->checkEditId('com_courses.edit.course', $id))
		{
			// Somehow the person just went to the form - we do not allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_courses&view=courses', false));

			return false;
		}

		parent::display();

		return $this;
	}
}
