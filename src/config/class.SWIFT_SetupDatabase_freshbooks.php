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
 * Setting up the functionality that is executed when a
 * app is either installed, upgraded or uninstalled
 *
 * @author Ashish Kataria
 */
class SWIFT_SetupDatabase_freshbooks extends SWIFT_SetupDatabase
{

	/**
	 * Constructor - Calls parent class constructor which sets the app name
	 *
	 * @author Varun Shoor
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __construct()
	{
		parent::__construct('freshbooks');

		return true;
	}

	/**
	 * Destructor - Calls parent class destructor
	 *
	 * @author Varun Shoor
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function __destruct()
	{
		parent::__destruct();

		return  true;
	}

	/**
	 * Creates table and indexes and import app settings
	 * Called when a app is installed
	 *
	 * @author Ashish Kataria
	 * @param int $_pageIndex The Page Index\
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Install($_pageIndex = 1)
	{
		parent::Install($_pageIndex);

		$this->ImportSettings();

		return true	;
	}

	/**
	 * Imports app settings and calls parent class upgrade function
	 * Called when a app is upgraded
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Upgrade()
	{
		$this->ImportSettings();

		return parent::Upgrade();
	}

	/**
	 * Calls parent class upgrade function
	 * Called when a app is uninstalled
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function Uninstall()
	{
		parent::Uninstall();

		return true;
	}

	/**
	 * Loads the table into the container
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	public function LoadTables()
	{
		$this->AddTable('freshbooksticketmap', new SWIFT_SetupDatabaseTable(TABLE_PREFIX . "freshbooksticketmap", "ticketid I NOTNULL" ));

		$this->AddIndex('freshbooksticketmap', new SWIFT_SetupDatabaseIndex("ticketid1", TABLE_PREFIX . "freshbooksticketmap", "ticketid"));

		return true;
	}

	/**
	 * Loads the settings from settings.xml file
	 *
	 * @author Ashish Kataria
	 * @return bool "true" on Success, "false" otherwise
	 */
	private function ImportSettings()
	{
		$this->Load->Library('Settings:SettingsManager');
		$this->SettingsManager->Import('./'.SWIFT_APPSDIRECTORY.'/freshbooks/config/settings.xml');

		return true;
	}

}
?>