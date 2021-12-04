<?php
defined('JPATH_BASE') or die;

//use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Factory;

class JFormFieldModalmess extends JFormField {
	
	
	protected $type = 'Modalmess';
	
	protected function getInput() {
		//<input name="" type="">

        HTMLHelper::_('behavior.modal','a.modal');
		
		
		if((int)$this->value > 0) {
			$db	= Factory::getDbo();
			$query = $db->getQuery(TRUE);
			
			$query->select($db->quoteName('title'));
			$query->from($db->quoteName('#__board_post'));
			
			$query->where($db->quoteName('id') . ' = ' . (int) $this->value);
			
			$db->setQuery($query);
			
			try {
				$title = $db->loadResult();
			}
			catch(RuntimeException $e) {
				JError::raiseWarning(500, $e->getMessage());
			
			}
		}
		
		
		if(empty($title)) {
			$title = Text::_('COM_BOARD_SELECT MESSAGE');
		}
		
		if((int)$this->value == 0) {
			$value = '';
		}
		else {
			$value = (int)$this->value;
		}
		
		$title = htmlspecialchars($title);
		
		$script = array();
		
		$script[] = 'function jSelectMessage(id,title) {';
		$script[] = 'document.getElementById("'.$this->id.'_id").value = id;';
		$script[] = 'document.getElementById("'.$this->id.'_name").value = title;';
		$script[] = 'jModalClose();';
		
		$script[] = ' }';
		
		Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

		
		$link = 'index.php?option=com_board&amp;view=messages&amp;layout=modal&amp;tmpl=component&amp;function=jSelectMessage';
		$html = array();
		
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="'.$this->id.'_name" value="'.$title.'" disabled="disabled" size="35" />';
		$html[] = '<a class="modal btn hasTooltip" title="'.HTMLHelper::tooltipText('COM_BOARD_CHANGE_MESSAGE')
            .'" href="'.$link.'&amp;'.Session::getFormToken().'=1" rel="{handler:\'iframe\',size:{x:800,y:450}}"><i class="icon-file"></i>'
            .Text::_('JSELECT').'</a>';
		$html[] = '</span>';
		
		
		$class = '';
		
		if($this->required) {
			$class = ' class="required modal-value"';
		}
		
		$html[] = '<input type="hidden" id="'.$this->id.'_id"'.$class.' name="'.$this->name.'" value="'.$value.'" />';
		
		
		return implode("\n",$html);
	}	
}

