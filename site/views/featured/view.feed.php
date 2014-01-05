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
 * Courses View class.
 *
 * @package     Courses
 * @subpackage  com_courses
 * @since       3.2
 */
class CoursesViewFeatured extends JViewLegacy
{
	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  The template file to include.
	 *
	 * @return  mixed  False on error, null otherwise.
	 *
	 * @since   3.2
	 */
	public function display($tpl = null)
	{
		// Parameters.
		$app       = JFactory::getApplication();
		$doc       = JFactory::getDocument();
		$params    = $app->getParams();
		$feedEmail = $app->getCfg('feed_email', 'author');
		$siteEmail = $app->getCfg('mailfrom');

		$doc->link = JRoute::_('index.php?option=com_courses&view=featured');

		// Get some data from the model.
		$app->input->set('limit', $app->getCfg('feed_limit'));
		$categories = JCategories::getInstance('Courses');
		$rows       = $this->get('Items');

		foreach ($rows as $row)
		{
			// Strip html from feed item title.
			$title = $this->escape($row->title);
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');

			// Compute the article slug.
			$row->slug = $row->alias ? ($row->id . ':' . $row->alias) : $row->id;

			// Url link to article.
			$link = JRoute::_(CoursesHelperRoute::getArticleRoute($row->slug, $row->catid));

			// Get row fulltext.
			$db = JFactory::getDbo();
			$query = 'SELECT' . $db->quoteName('fulltext') . 'FROM #__courses WHERE id =' . $row->id;
			$db->setQuery($query);
			$row->fulltext = $db->loadResult();

			$description = ($params->get('feed_summary', 0) ? $row->description : JHtml::_('string.truncate', $row->description, 200, true, true));
			$author      = $row->created_by_alias ? $row->created_by_alias : $row->author;

			// Load individual item creator class.
			$item           = new JFeedItem;
			$item->title    = $title;
			$item->link     = $link;
			$item->date     = $row->publish_up;
			$item->category = array();

			// All featured articles are categorized as "Featured".
			$item->category[] = JText::_('JFEATURED');

			for ($item_category = $categories->get($row->catid); $item_category !== null; $item_category = $item_category->getParent())
			{
				if ($item_category->id > 1)
				{
					// Only add non-root categories.
					$item->category[] = $item_category->title;
				}
			}

			$item->author = $author;

			if ($feedEmail == 'site')
			{
				$item->authorEmail = $siteEmail;
			}
			elseif ($feedEmail === 'author')
			{
				$item->authorEmail = $row->author_email;
			}

			// Add readmore link to description.
			if (!$params->get('feed_summary', 0) && $params->get('feed_show_readmore', 0) && $row->fulltext)
			{
				$description .= '<p class="feed-readmore"><a target="_blank" href ="' . $item->link . '">' . JText::_('COM_COURSES_FEED_READMORE') . '</a></p>';
			}

			// Load item description and add div.
			$item->description = '<div class="feed-description">' . $description . '</div>';

			// Loads item info into rss array.
			$doc->addItem($item);
		}
	}
}
