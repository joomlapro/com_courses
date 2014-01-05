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
 * Courses categories view.
 *
 * @package     Courses
 * @subpackage  com_courses
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class CoursesViewCategories extends JViewCategories
{
	/**
	 * Language key for default page heading.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $pageHeading = 'COM_COURSES_DEFAULT_PAGE_TITLE';

	/**
	 * The name of the extension for the category.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $extension = 'com_courses';
}
