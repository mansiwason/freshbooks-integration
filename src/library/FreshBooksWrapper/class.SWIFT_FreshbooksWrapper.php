<?php
/**
 * ###############################################
 *
 * Freshbooks Integration
 * _______________________________________________
 *
 * @author         Varun Shoor
 *
 * @package        Freshbooks Integration
 * @copyright      Copyright (c) 2001-2015, Kayako
 * @license        http://www.kayako.com/license
 * @link           http://www.kayako.com
 *
 * ###############################################
 */

/**
 * Wrapper class for using freshbooks thirdparty
 *
 * @author Ashish Kataria
 */
class SWIFT_FreshbooksWrapper extends SWIFT_Library
{

	/**
	 * Used for storing third party library class object
	 *
	 * @access private
	 * @var string
	 */
	private $_freshbooksObject;

	/**
	 * Constructor
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __construct()
	{
		parent::__construct();

		require_once ('./' . SWIFT_APPSDIRECTORY .'/freshbooks/' . SWIFT_THIRDPARTYDIRECTORY . '/jboesch-FreshBooksRequest-PHP-API/lib/FreshBooksRequest.php');

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
	 * Sets API Url and API token
	 *
	 * @author Ashish Kataria
	 */
	public function SetApiDetails($_freshbooksURL, $_freshbooksToken)
	{
		FreshBooksRequest::init(trim($_freshbooksURL), trim($_freshbooksToken));
	}

	/**
	 * Test the API connection and return projects list
	 *
	 * @author Ashish Kataria
	 */
	public function TestConnection()
	{
		$_SWIFT = SWIFT::GetInstance();

		$this->_freshbooksObject = new FreshBooksRequest('system.current');
		$this->_freshbooksObject ->request();

		return $this->_freshbooksObject ->success();
	}

	/**
	 * Return projects list
	 *
	 * @author Ashish Kataria
	 *
	 * @param string $_type    (OPTIONAL)
	 * @param int    $_staffId (OPTIONAL)
	 *
	 * @return array
	 */
	public function GetProjectList($_type = "json", $_staffId = 0)
	{
		$_projectList      = array();
		$_projectContainer = $this->GetPerPageList('project.list');

		$_totalPage = $_projectContainer['projects']['@attributes']['pages'];
		if (isset($_projectContainer['projects']['project'])) {
			$_projectList = $_projectContainer['projects']['project'];
		}

		if ($_totalPage > 1) {
			for ($_page = 2; $_page <= $_totalPage; $_page++) {
				$_response = $this->GetPerPageList('project.list', $_page);

				$_projectContainer['@attributes']             = $_response['@attributes'];
				$_projectContainer['projects']['@attributes'] = $_response['projects']['@attributes'];

				$_projectList = array_merge($_projectList, $_response['projects']['project']);
			}
			$_projectContainer['projects']['project'] = $_projectList;
		}

		if ($_type == "json") {
			echo json_encode($_projectContainer);
		} else {
			return $_projectContainer;
		}
	}

	/**
	 * Return per page list
	 *
	 * @author Abhishek Mittal
	 *
	 * @param string $_listType
	 * @param int    $_page (OPTIONAL)
	 *
	 * @return array
	 * @throws SWIFT_Exception If request fail
	 */
	public function GetPerPageList($_listType, $_page = 1)
	{
		$this->_freshbooksObject = new FreshBooksRequest($_listType);

		$this->_freshbooksObject->post(array(
											'page'     => $_page,
											'per_page' => 100
									   ));

		$this->_freshbooksObject->request();

		if ($this->_freshbooksObject->success()) {
			return $this->_freshbooksObject->getResponse();
		} else {
			throw new SWIFT_Exception($this->Language->Get('request_fail_error'));
		}
	}

	/**
	 * Return staff list
	 *
	 * @author Ashish Kataria
	 */
	public function GetStaffList()
	{
		$this->_freshbooksObject = new FreshBooksRequest('staff.list');
		$this->_freshbooksObject->request();

		if($this->_freshbooksObject->success())
		{
			echo json_encode($this->_freshbooksObject->getResponse());
		} else {
			throw new SWIFT_Exception($this->Language->Get('request_fail_error'));
		}
	}

	/**
	 * Return task list for specific project
	 *
	 * @author Ashish Kataria
	 * @param int $_projectid
	 * @param string $_type
	 */
	public function GetTaskList($_projectid, $_type="json")
	{
		$this->_freshbooksObject = new FreshBooksRequest('task.list');
		$this->_freshbooksObject->post(array(
			'project_id' => $_projectid
		));

		$this->_freshbooksObject->request();

		if ($this->_freshbooksObject->success())
		{
			if ($_type == "json") {
				echo json_encode($this->_freshbooksObject->getResponse());
			} else {
				return $this->_freshbooksObject->getResponse();
			}

		} else {
			throw new SWIFT_Exception($this->Language->Get('request_fail_error'));
		}
	}

	/**
	 * Creates time entry in freshbooks
	 *
	 * @author Ashish Kataria
	 * @param int $_projectId Project Id
	 * @param int $_taskId Task Id
	 * @param int $_staffId Staff Id
	 * @param float $_hours Numbers of billable hours
	 * @param mixed $_date Date on which task was billed
	 * @param string $_notes Notes
	 */
	public function CreateTimeEntry($_projectId, $_taskId, $_staffId, $_hours, $_date, $_notes = "none")
	{
		if (!$_projectId && !$_taskId && !$_staffId) {
			throw new SWIFT_Exception($this->Language->Get('error'));
		}

		$this->_freshbooksObject = new FreshBooksRequest('time_entry.create');

		$this->_freshbooksObject->post(array(
			"time_entry" => array(
			"project_id" => $_projectId,
			"task_id"	=> $_taskId,
			"staff_id"	=> $_staffId,
			"hours"	=> $_hours,
			"notes"	=> $_notes,
			"date"	=> $_date
		)));

		$this->_freshbooksObject->request();

		if (!$this->_freshbooksObject->success())
		{
			throw new SWIFT_Exception($this->Language->Get('request_fail_error'));
		}
	}
}