<?php
final class Konzert_EditTermin extends GWF_Method
{
	public function getUserGroups() { return array('admin','staff'); }
	
	public function execute()
	{
		if (false === ($termin = Konzert_Termin::getByID(Common::getGetString('ktid'))))
		{
			return $this->module->error('err_termin');
		}
		
		if (isset($_POST['edit']))
		{
			return $this->onEdit($termin).$this->templateEdit($termin);
		}
		
		return $this->templateEdit($termin);
	}
	
	private function templateEdit(Konzert_Termin $termin)
	{
		$form = $this->formEdit($termin);
		$tVars = array(
			'form' => $form->templateY($this->module->lang('ft_edit')),
		);
		return $this->module->template('at_edit.tpl', $tVars);
	}
	
	private function formEdit(Konzert_Termin $termin)
	{
		$data = array();
		$data['date'] = array(GWF_Form::DATE_FUTURE, $termin->getVar('kt_date'), $this->module->lang('th_date'), '', GWF_Date::LEN_DAY);
		$data['time'] = array(GWF_Form::TIME, $termin->getVar('kt_time'), $this->module->lang('th_time'));
		$data['prog'] = array(GWF_Form::STRING, $termin->getVar('kt_prog'), $this->module->lang('th_prog'));
		$data['city'] = array(GWF_Form::STRING, $termin->getVar('kt_city'), $this->module->lang('th_city'));
		$data['location'] = array(GWF_Form::STRING, $termin->getVar('kt_location'), $this->module->lang('th_location'));
		$data['tickets'] = array(GWF_Form::STRING, $termin->getVar('kt_tickets'), $this->module->lang('th_tickets'));
		$data['enabled'] = array(GWF_Form::CHECKBOX, $termin->isEnabled(), $this->module->lang('th_enabled'));
		$data['edit'] = array(GWF_Form::SUBMIT, $this->module->lang('btn_edit'));
		return new GWF_Form($this, $data);
	}
	
	public function validate_date($m, $arg) { return GWF_Validator::validateDate($m, 'date', $arg, GWF_Date::LEN_DAY, false); }
	public function validate_time($m, $arg) { return GWF_Validator::validateTime($m, 'time', $arg, false, true); }
	public function validate_prog($m, $arg) { return GWF_Validator::validateString($m, 'prog', $arg, 2, 128); }
	public function validate_city($m, $arg) { return GWF_Validator::validateString($m, 'city', $arg, 2, 63); }
	public function validate_location($m, $arg) { return GWF_Validator::validateString($m, 'location', $arg, 2, 128); }
	public function validate_tickets($m, $arg) { return GWF_Validator::validateString($m, 'tickets', $arg, 2, 128); }
	
	private function onEdit(Konzert_Termin $termin)
	{
		$form = $this->formEdit($termin);
		if (false !== ($error = $form->validate($this->module)))
		{
			return $error;
		}
		
		$options = 0;
		$options |= isset($_POST['enabled']) ? Konzert_Termin::ENABLED : 0;
		
		if (false === $termin->saveVars(array(
			'kt_date' => $form->getVar('date'),
			'kt_time' => $form->getVar('time'),
			'kt_city' => $form->getVar('city'),
			'kt_prog' => $form->getVar('prog'),
			'kt_tickets' => $form->getVar('tickets'),
			'kt_location' => $form->getVar('location'),
			'kt_options' => $options,
		)))
		{
			return GWF_HTML::err('ERR_DATABASE', array(__FILE__, __LINE__));
		}
		
		return $this->module->message('msg_t_edited');
	}
}
?>