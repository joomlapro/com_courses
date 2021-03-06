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

// Load the tooltip bootstrap script.
JHtml::_('bootstrap.tooltip');

// Get the language.
$lang = JFactory::getLanguage();

$class = ' class="first"';

if (count($this->children[$this->category->id]) > 0):
	foreach ($this->children[$this->category->id] as $id => $child):
		if ($this->params->get('show_empty_categories') || $child->getNumItems(true) || count($child->getChildren())):
			if (!isset($this->children[$this->category->id][$id + 1]))
			{
				$class = ' class="last"';
			}
		?>
			<div<?php echo $class; ?>>
				<?php $class = ''; ?>
				<?php if ($lang->isRTL()): ?>
					<h3 class="page-header item-title">
						<?php if ( $this->params->get('show_cat_num_courses', 1)): ?>
							<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::tooltipText('COM_COURSES_NUM_ITEMS'); ?>">
								<?php echo $child->getNumItems(true); ?>
							</span>
						<?php endif; ?>
						<a href="<?php echo JRoute::_(CoursesHelperRoute::getCategoryRoute($child->id)); ?>"><?php echo $this->escape($child->title); ?></a>
						<?php if (count($child->getChildren()) > 0): ?>
							<a href="#category-<?php echo $child->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right"><span class="icon-plus"></span></a>
						<?php endif; ?>
					</h3>
				<?php else: ?>
					<h3 class="page-header item-title">
						<a href="<?php echo JRoute::_(CoursesHelperRoute::getCategoryRoute($child->id)); ?>"><?php echo $this->escape($child->title); ?></a>
						<?php if ( $this->params->get('show_cat_num_courses', 1)): ?>
							<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::tooltipText('COM_COURSES_NUM_ITEMS'); ?>">
								<?php echo $child->getNumItems(true); ?>
							</span>
						<?php endif; ?>
						<?php if (count($child->getChildren()) > 0): ?>
							<a href="#category-<?php echo $child->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right"><span class="icon-plus"></span></a>
						<?php endif; ?>
					</h3>
				<?php endif; ?>
				<?php if ($this->params->get('show_subcat_desc') == 1): ?>
					<?php if ($child->description): ?>
						<div class="category-desc">
							<?php echo JHtml::_('content.prepare', $child->description, '', 'com_courses.category'); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if (count($child->getChildren()) > 0): ?>
					<div class="collapse fade" id="category-<?php echo $child->id; ?>">
						<?php
						$this->children[$child->id] = $child->getChildren();
						$this->category = $child;
						$this->maxLevel--;

						if ($this->maxLevel != 0)
						{
							echo $this->loadTemplate('children');
						}

						$this->category = $child->getParent();
						$this->maxLevel++;
						?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif;
