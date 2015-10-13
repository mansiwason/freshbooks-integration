<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/freshbooks-integration
 */

/**
 * Controller class for handling ajax requests
 *
 * @author Ashish Kataria
 */
class Controller_AjaxRequest extends Controller_admin
{
	// Core Constants
	const MENU_ID = 1;
	const NAVIGATION_ID = 2;

	/**
	 * Used for storing third party library object
	 *
	 * @access private
	 * @var string
	 */
	private $_freshbooksWrapperObject;

	/**
	 * Constructor
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __construct()
	{
		parent::__construct();
		$this->Language->Load('freshbooks');
		$this->Load->Library('FreshBooksWrapper:FreshbooksWrapper');
		return true;
	}

	/**
	 * Destructor
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return true;
	}

	/**
	 *  Verify connection of freshbooks api
	 *
	 * @author Ashish Kataria
	 */
	public function VerifyConnection($_chunkContainer = '')
	{
		$this->_freshbooksWrapperObject = new SWIFT_FreshbooksWrapper();

		if (!empty($_chunkContainer)) {
			parse_str(base64_decode($_chunkContainer), $_POST);
		}

		$this->UserInterface->Header($this->Language->Get('freshbooks_name'), self::MENU_ID, self::NAVIGATION_ID);

		if (isset($_POST['apiurl']) && isset($_POST['apitoken']) && !empty($_POST['apiurl']) && !empty($_POST['apitoken']))
		{
			$this->_freshbooksWrapperObject->SetApiDetails($_POST['apiurl'], $_POST['apitoken']);

			if ($this->_freshbooksWrapperObject->TestConnection()) {
				$this->View->RenderConnectionButton($this->Language->Get('connection_ok'), true);
			}
			else {
				$this->View->RenderConnectionButton($this->Language->Get('request_fail_error'));
			}
		} else {
			$this->View->RenderConnectionButton($this->Language->Get('invalid_data_error'));
		}

		$this->UserInterface->Footer();
	}

	/**
	 *  Get list of projects
	 *
	 * @author Ashish Kataria
	 */
	public function GetProjects()
	{
		$this->_freshbooksWrapperObject = new SWIFT_FreshbooksWrapper();

		if (isset($_POST['apiurl']) && isset($_POST['apitoken']) && !empty($_POST['apiurl']) && !empty($_POST['apitoken']))
		{
			$this->_freshbooksWrapperObject->SetApiDetails($_POST['apiurl'], $_POST['apitoken']);
			$this->_freshbooksWrapperObject->GetProjectList();
		} else {
			$this->UserInterface->Header($this->Language->Get('freshbooks_name'), self::MENU_ID, self::NAVIGATION_ID);$this->View->RenderConnectionButton($this->Language->Get('invalid_data_error'));$this->UserInterface->Footer();
		}
	}

	/**
	 *  Return the tasks for specific project
	 *
	 * @author Ashish Kataria
	 */
	public function ProjectTask()
	{
		$this->_freshbooksWrapperObject = new SWIFT_FreshbooksWrapper();

		$this->_freshbooksWrapperObject->SetApiDetails($_POST['apiurl'], $_POST['apitoken']);
		$this->_freshbooksWrapperObject->GetTaskList($_POST['projectid']);
	}

}