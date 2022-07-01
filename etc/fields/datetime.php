<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldDateTime extends JFormField {

    protected $type = 'DateTime';

    public function getInput() {
    	$html = '<input  id="'.$this->id.'" name="'.$this->name.'" data-format="g:i PP" type="text" value="'.$this->value.'"></input>'.
              '<span class="icon-clock" aria-hidden="true"></span>'.
              '<script type="text/javascript">'.
                'jQuery(function() {
                	jQuery("#'.$this->id.'").datetimepicker({
									 	datepicker:false,
									  formatTime: "g:i A",
    								format : "g:i A"
									});
                });'.
              '</script>';
      return $html;
    }
    public function setup(SimpleXMLElement $element, $value, $group = null)
		{

			// Add CSS and JS
			$doc = \JFactory::getDocument();
			$doc->addStylesheet(JUri::root(true).'/templates/ja_findus/js/datetime/jquery.datetimepicker.min.css');
			$doc->addStylesheet(JUri::root(true).'/templates/t4_bs5_blank/js/datetime/style.css');
			$doc->addScript(JUri::root(true).'/templates/ja_findus/js/datetime/jquery.datetimepicker.full.js');
		
			return parent::setup($element, $value, $group);
		}
}