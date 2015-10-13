<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/freshbooks-integration
 */

/**
 * The Email Queue View
 *
 * @author Varun Shoor
 */
class View_AjaxRequest extends SWIFT_View
{
	/**
	 * Constructor
	 *
	 * @author Varun Shoor
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __construct()
	{
		parent::__construct();

		return true;
	}

	/**
	 * Destructor
	 *
	 * @author Varun Shoor
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 * Render the Verify Connection Dialog
	 *
	 * @author Ashish Kataria
	 * @param $_msg The message to display
	 * @param $_displayError To show error or not
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function RenderConnectionButton($_msg, $_displayError = 0)
	{
		$_SWIFT = SWIFT::GetInstance();

		if (!$this->GetIsClassLoaded())
		{
			return false;
		}

		// Calculate the URL
		$this->UserInterface->Start(get_class($this) . 'verifycon', '/Base/Settings/View', SWIFT_UserInterface::MODE_EDIT, true);
		$this->UserInterface->SetDialogOptions(false);

		if ($_displayError)
		{
			$this->UserInterface->Toolbar->AddButton('Ok', 'icon_jqmcompleted.png', 'javascript: UIDestroyAllDialogs(); SWIFT.Freshbooks.AdminObject.ConnectionButtonOk();', '4', '', '');
			$_statusImage = 'icon_check.gif';
			$_customClass = '';
		}
		else {
			$this->UserInterface->Toolbar->AddButton('Ok', 'icon_jqmcompleted.png', 'javascript: UIDestroyAllDialogs();', '4', '', '');
			$_statusImage = 'icon_block.gif';
			$_customClass = 'errorrow';
		}

		$this->UserInterface->Toolbar->AddButton($this->Language->Get('help'), 'icon_help.gif', SWIFT_Help::RetrieveHelpLink('parseremailqueue'),
				SWIFT_UserInterfaceToolbar::LINK_NEWWINDOW);

		/*
		 * ###############################################
		 * BEGIN GENERAL TAB
		 * ###############################################
		 */
		$_GeneralTabObject = $this->UserInterface->AddTab($this->Language->Get('tabgeneral'), 'icon_form.gif', 'general', true);

		$_columnContainer[0]['value'] = $_msg;
		$_columnContainer[0]['align'] = 'left';

		$_columnContainer[1]['value'] = '<img src="' . SWIFT::Get('themepath') . 'images/' . $_statusImage .
		'" align="absmiddle" border="0" />';
		$_columnContainer[1]['align'] = 'center';
		$_columnContainer[1]['width'] = '16';

		$_GeneralTabObject->Row($_columnContainer, $_customClass);

		/*
		 * ###############################################
		 * END GENERAL TAB
		 * ###############################################
		 */
		$this->UserInterface->End();

		return false;
	}
}