<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formrule');


class JFormRuleName extends JFormRule {

    protected $regex = '^[^0-9]+$';

}