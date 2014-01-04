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

// Load the behavior script.
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Initialiase variables.
$this->hiddenFieldsets    = array();
$this->hiddenFieldsets[0] = 'basic-limited';
$this->configFieldsets    = array();
$this->configFieldsets[0] = 'editorConfig';

// Create shortcut to parameters.
$params = $this->state->get('params');

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);

if (!isset($params->show_publishing_options))
{
	$params->show_publishing_options = '1';
	$params->show_course_options = '1';
	$params->show_images_backend = '0';
	$params->show_images_frontend = '0';
}

// Check if the course uses configuration settings besides global. If so, use them.
if (isset($this->item->params['show_publishing_options']) && $this->item->params['show_publishing_options'] != '')
{
	$params->show_publishing_options = $this->item->params['show_publishing_options'];
}

if (isset($this->item->params['show_course_options']) && $this->item->params['show_course_options'] != '')
{
	$params->show_course_options = $this->item->params['show_course_options'];
}

if (isset($this->item->params['show_images_frontend']) && $this->item->params['show_images_frontend'] != '')
{
	$params->show_images_frontend = $this->item->params['show_images_frontend'];
}

if (isset($this->item->params['show_images_backend']) && $this->item->params['show_images_backend'] != '')
{
	$params->show_images_backend = $this->item->params['show_images_backend'];
}

// Add JavaScript Frameworks.
JHtml::_('jquery.framework');

// Load JavaScript.
JHtml::script('com_courses/jquery.bootbox.min.js', false, true);
JHtml::script('com_courses/moment.min.js', false, true);
JHtml::script('com_courses/jquery.combodate.js', false, true);

// Get the full current URI.
$return = urlencode(base64_encode(JUri::getInstance()));
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'course.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}

	jQuery(document).ready(function($) {
		// Delete the entry once we have confirmed that it should be deleted.
		$('a.delete').live('click', function(event) {
			event.preventDefault();

			var row = $(event.currentTarget).closest('tr')
				cid = $(this).data('id');

			bootbox.confirm('<?php echo JText::_('COM_COURSES_CONFIRM_PROCEED_DELETE'); ?>', '<?php echo JText::_('JNO'); ?>', '<?php echo JText::_('JYES'); ?>', function(result) {
				if (result) {
					$.ajax({
						url: 'index.php?option=com_courses&task=lesson.deleteLessonAjax&tmpl=component&format=json',
						type: 'POST',
						data: {
							cid: cid,
							'<?php echo JSession::getFormToken(); ?>': 1
						},
						success: function() {
							$(row).remove();
						}
					});
				};
			});
		});

		$('.datetime').combodate();
	});
</script>
<form action="<?php echo JRoute::_('index.php?option=com_courses&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_COURSES_FIELDSET_COURSE_CONTENT', true)); ?>
				<div class="row-fluid">
					<div class="span9">
						<fieldset class="adminform">
							<?php echo $this->form->getControlGroup('date_start'); ?>
							<?php echo $this->form->getControlGroup('date_end'); ?>
							<?php echo $this->form->getControlGroup('vacancies'); ?>
							<?php echo $this->form->getInput('description'); ?>
						</fieldset>
					</div>
					<div class="span3">
						<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'lessons', JText::_('COM_COURSES_FIELDSET_LESSONS', true)); ?>
				<?php echo JHtml::_('link', 'index.php?option=com_courses&task=lesson.add&course_id=' . $this->item->id . '&return=' . $return, JText::_('COM_COURSES_ADD_LESSON'), array('class' => 'btn btn-success')); ?>
				<br><br>
				<?php
				$saveOrderingUrl = 'index.php?option=com_courses&task=lessons.saveOrderAjax&tmpl=component';
				JHtml::_('sortablelist.sortable', 'lessonList', 'item-form', 'asc', $saveOrderingUrl);
				?>
				<?php if ($this->lessons): ?>
					<table class="table table-striped table-hover" id="lessonList">
						<thead>
							<tr>
								<th width="1%" class="nowrap center hidden-phone">
									<i class="icon-menu-2"></i>
								</th>
								<th class="title">
									<?php echo JText::_('COM_COURSES_HEADING_LESSON'); ?>
								</th>
								<th width="5%" class="nowrap hidden-phone">
									<?php echo JText::_('COM_COURSES_HEADING_ACTIONS'); ?>
								</th>
								<th width="1%" class="nowrap center hidden-phone">
									<?php echo JText::_('JGRID_HEADING_ID'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->lessons as $i => $lesson): ?>
								<tr class="row<?php echo $i % 2; ?>">
									<td class="order nowrap center hidden-phone">
										<span class="sortable-handler hasTooltip" title="">
											<i class="icon-menu"></i>
										</span>
										<input type="checkbox" style="display:none" name="cid[]" value="<?php echo $lesson->id; ?>" />
										<input type="text" style="display:none" name="order[]" value="<?php echo $lesson->ordering; ?>" />
									</td>
									<td class="nowrap">
										<?php echo $lesson->title; ?>
									</td>
									<td class="small nowrap hidden-phone">
										<?php echo JHtml::_('link', 'index.php?option=com_courses&task=lesson.edit&id=' . $lesson->id . '&return=' . $return, JText::_('JACTION_EDIT'), array('class' => 'btn btn-default')); ?>
										<a href="#" class="btn btn-danger delete" data-id="<?php echo $lesson->id; ?>"><?php echo JText::_('JACTION_DELETE'); ?></a>
									</td>
									<td class="center hidden-phone">
										<?php echo (int) $lesson->id; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else: ?>
					<div class="alert">
						<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php endif; ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php // Do not show the publishing options if the edit form is configured not to. ?>
			<?php if ($params->show_publishing_options == 1): ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_COURSES_FIELDSET_PUBLISHING', true)); ?>
					<div class="row-fluid form-horizontal-desktop">
						<div class="span6">
							<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
						</div>
						<div class="span6">
							<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
						</div>
					</div>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>

			<?php // Do not show the images and links options if the edit form is configured not to. ?>
			<?php if ($params->show_images_backend == 1): ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'images', JText::_('COM_COURSES_FIELDSET_IMAGES', true)); ?>
					<div class="row-fluid form-horizontal-desktop">
						<div class="span6">
							<?php echo $this->form->getControlGroup('images'); ?>
							<?php foreach ($this->form->getGroup('images') as $field): ?>
								<?php echo $field->getControlGroup(); ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>

			<?php if (JLanguageAssociations::isEnabled()): ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS', true)); ?>
					<?php echo JLayoutHelper::render('joomla.edit.associations', $this); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>

			<?php $this->show_options = $params->show_course_options; ?>
			<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

			<?php if ($this->canDo->get('core.admin')): ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'editor', JText::_('COM_COURSES_FIELDSET_SLIDER_EDITOR_CONFIG', true)); ?>
					<?php foreach ($this->form->getFieldset('editorConfig') as $field): ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>

			<?php if ($this->canDo->get('core.admin')): ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_COURSES_FIELDSET_RULES', true)); ?>
					<?php echo $this->form->getInput('rules'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getBase64('return'); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>
