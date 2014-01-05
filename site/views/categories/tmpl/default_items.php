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

$class = ' class="first"';

// Load the tooltip bootstrap script.
JHtml::_('bootstrap.tooltip');

// Get the language.
$lang = JFactory::getLanguage();

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0):
	foreach($this->items[$this->parent->id] as $id => $item):
		if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())):
			if (!isset($this->items[$this->parent->id][$id + 1]))
			{
				$class = ' class="last"';
			}
			?>
			<div <?php echo $class; ?> >
				<?php $class = ''; ?>
				<h3 class="page-header item-title">
					<a href="<?php echo JRoute::_(CoursesHelperRoute::getCategoryRoute($item->id)); ?>">
					<?php echo $this->escape($item->title); ?></a>
					<?php if ($this->params->get('show_cat_num_courses_cat') == 1): ?>
						<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::tooltipText('COM_COURSES_NUM_ITEMS'); ?>">
							<?php echo $item->numitems; ?>
						</span>
					<?php endif; ?>
					<?php if (count($item->getChildren()) > 0): ?>
						<a href="#category-<?php echo $item->id; ?>" data-toggle="collapse" data-toggle="button" class="btn btn-mini pull-right"><span class="icon-plus"></span></a>
					<?php endif; ?>
				</h3>
				<?php if ($this->params->get('show_description_image') && $item->getParams()->get('image')): ?>
					<img src="<?php echo $item->getParams()->get('image'); ?>"/>
				<?php endif; ?>
				<?php if ($this->params->get('show_subcat_desc_cat') == 1): ?>
					<?php if ($item->description): ?>
						<div class="category-desc">
							<?php echo JHtml::_('content.prepare', $item->description, '', 'com_courses.categories'); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if (count($item->getChildren()) > 0): ?>
					<div class="collapse fade" id="category-<?php echo $item->id; ?>">
						<?php
						$this->items[$item->id] = $item->getChildren();
						$this->parent = $item;
						$this->maxLevelcat--;

						echo $this->loadTemplate('items');

						$this->parent = $item->getParent();
						$this->maxLevelcat++;
						?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif;
