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

?>
<div class="courses course-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th class="title">
					<?php echo JText::_('COM_COURSES_HEADING_TITLE'); ?>
				</th>
				<th width="5%" class="nowrap hidden-phone">
					<?php echo JText::_('COM_COURSES_HEADING_START'); ?>
				</th>
				<th width="5%" class="nowrap hidden-phone">
					<?php echo JText::_('COM_COURSES_HEADING_END'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->items as $i => $item): ?>
				<tr>
					<td class="title">
						<a href="<?php echo $item->link; ?>"><?php echo $this->escape($item->title); ?></a>
					</td>
					<td class="small nowrap hidden-phone">
						<?php if ($item->date_start != '0000-00-00 00:00:00'): ?>
							<span class="label label-info"><?php echo JHtml::_('date', $item->date_start, JText::_('COM_COURSES_TIME_FORMAT')); ?></span><br>
							<small><?php echo JHtml::_('date', $item->date_start, JText::_('COM_COURSES_DATE_FORMAT')); ?></small>
						<?php endif; ?>
					</td>
					<td class="small nowrap hidden-phone">
						<?php if ($item->date_end != '0000-00-00 00:00:00'): ?>
							<span class="label"><?php echo JHtml::_('date', $item->date_end, JText::_('COM_COURSES_TIME_FORMAT')); ?></span><br>
							<small><?php echo JHtml::_('date', $item->date_end, JText::_('COM_COURSES_DATE_FORMAT')); ?></small>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php if ($this->params->get('show_pagination', 1)): ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)): ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
</div>
