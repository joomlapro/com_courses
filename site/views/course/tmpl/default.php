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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();

// Load the tooltip behavior script.
JHtml::_('behavior.caption');
?>
<div class="courses course-item<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<div class="page-header">
		<h2>
			<?php echo $this->escape($this->item->title); ?>
			<?php if ($vacancies = $this->item->vacancies): ?>
				<div class="pull-right">
					<?php echo JText::sprintf('COM_COURSES_VACANCIES_NUMBER', $vacancies); ?>
				</div>
			<?php endif; ?>
		</h2>
	</div>

	<?php if ($description = $this->item->description): ?>
		<div class="description">
			<?php echo $description; ?>
		</div>
	<?php endif; ?>

	<?php if ($this->lessons): ?>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th width="5%" class="nowrap hidden-phone">
						<?php echo JText::_('COM_COURSES_HEADING_NUMBER'); ?>
					</th>
					<th class="title">
						<?php echo JText::_('COM_COURSES_HEADING_TITLE'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->lessons as $i => $lesson): ?>
					<tr>
						<td class="nowrap hidden-phone">
							<?php echo $i + 1; ?>
						</td>
						<td class="title">
							<?php echo $this->escape($lesson->title); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
