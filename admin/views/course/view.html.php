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
 * View to edit a course.
 *
 * @package     Courses
 * @subpackage  com_courses
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class CoursesViewCourse extends JViewLegacy
{
	/**
	 * The form to use for the view.
	 *
	 * @var     JForm
	 */
	protected $form;

	/**
	 * The item to edit.
	 *
	 * @var     JObject
	 */
	protected $item;

	/**
	 * The model state.
	 *
	 * @var     JObject
	 */
	protected $state;

	protected $lessons;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   3.2
	 */
	public function display($tpl = null)
	{
		try
		{
			// Initialiase variables.
			$this->form  = $this->get('Form');
			$this->item  = $this->get('Item');
			$this->state = $this->get('State');
			$this->canDo = CoursesHelper::getActions($this->state->get('filter.category_id'), 0, 'com_courses');

			// Get an instance of the generic lessons model.
			$model = JModelLegacy::getInstance('Lessons', 'CoursesModel', array('ignore_request' => true));
			$model->setState('list.select', 'a.id, a.title, a.state, a.ordering');
			$model->setState('list.ordering', 'a.ordering');
			$model->setState('list.direction', 'asc');
			$model->setState('filter.course_id', $this->item->id);

			$this->lessons = $model->getItems();
		}
		catch (Exception $e)
		{
			JErrorPage::render($e);

			return false;
		}

		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
			$this->form->setFieldAttribute('catid', 'readonly', 'true');
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		// Initialiase variables.
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Since we do not track these assets at the item level, use the category id.
		$canDo      = $this->canDo;

		JToolbarHelper::title(JText::_('COM_COURSES_PAGE_' . ($checkedOut ? 'VIEW_COURSE' : ($isNew ? 'ADD_COURSE' : 'EDIT_COURSE'))), 'pencil-2 course-add');

		// Built the actions for new and existing records.
		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_courses', 'core.create')) > 0))
		{
			JToolbarHelper::apply('course.apply');
			JToolbarHelper::save('course.save');
			JToolbarHelper::save2new('course.save2new');
			JToolbarHelper::cancel('course.cancel');
		}
		else
		{
			// Can not save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('course.apply');
					JToolbarHelper::save('course.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('course.save2new');
					}
				}
			}

			// If checked out, we can still save.
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('course.save2copy');
			}

			if ($this->state->params->get('save_history', 1) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_courses.course', $this->item->id);
			}

			JToolbarHelper::cancel('course.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::help('course', $com = true);
	}
}
