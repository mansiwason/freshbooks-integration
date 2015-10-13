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
class Controller_AjaxRequest extends Controller_staff
{

	/**
	 * Used for storing third party library class object
	 *
	 * @access private
	 * @var string
	 */
	private $_freshbooksWrapperObject;

	/**
	 * Constructor
	 *
	 * @author Varun Shoor
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __construct()
	{
		parent::__construct();

		$this->Load->Library('FreshBooksWrapper:FreshbooksWrapper');

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
	 * Intialize wrapper object and calls function send as action in post ajax request
	 *
	 * @author Ashish Kataria
	 */
	public function Index()
	{
		// Create instance of SWIFT class
		$_SWIFT = SWIFT::GetInstance();

		// Create instance of freshbooks wrapper
		$this->_freshbooksWrapperObject = new SWIFT_FreshbooksWrapper();

		// Set freshbooks API Url and authentication token
		$this->_freshbooksWrapperObject->SetApiDetails($_SWIFT->Settings->Get('freshbooks_apiUrl'), $_SWIFT->Settings->Get('freshbooks_authenticationToken'));

		call_user_func(array(__CLASS__, $_POST['action']));
	}

	/**
	 *  Verify connection of freshbooks api
	 *
	 * @author Ashish Kataria
	 */
	public function VerifyConnection()
	{
		$this->_freshbooksWrapperObject->TestConnection();
	}

	/**
	 * Returns list of projects corresponding to specific staff
	 *
	 * @author Ashish Kataria
	 */
	public function ProjectsList()
	{
		$this->_freshbooksWrapperObject->GetProjectList("json", $_POST['staffId']);
	}

	/**
	 *  Get the tasks for specific project
	 *
	 * @author Ashish Kataria
	 * @return string
	 */
	public function ProjectTask()
	{
		$this->_freshbooksWrapperObject->GetTaskList($_POST['projectid']);
	}

	/**
	 *  Get Staff List
	 *
	 * @author Ashish Kataria
	 * @return string
	 */
	public function UserList()
	{
		$this->_freshbooksWrapperObject->GetStaffList();
	}
}