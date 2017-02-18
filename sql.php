<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Database connector class.
 *
 * @since       11.1
 * @deprecated  13.3 (Platform) & 4.0 (CMS)
 */
class JSearchIndexSql extends JSearchIndex
{
	/**
	 * Method to Add record on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function AddRecord()
	{
	}

	/**
	 * Method to Get record on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function getRecord()
	{
	}

	/**
	 * Method to  Set ID
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function SetId()
	{
	}

	/**
	 * Method to  AddSeparator
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function AddSeparator()
	{
	}

	/**
	 * Method to  commit files to  respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function Commit()
	{
	}

	/**
	 *  Method to delete on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function DeleteRecord()
	{
	}

	/**
	 * Method to delete algolia or solr or any other
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function ClearRecords()
	{
	}

	/**
	 * Method to Add the search phrase, when you’re searching for specific search phrase Default is empty
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function SetSearchWord()
	{
	}

	/**
	 * Method to get records based on filters
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function SetFilters()
	{
	}

	/**
	 * Method to set Facets
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function SetFacets()
	{
	}

	/**
	 * Method to get count of records
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function GetCount()
	{
	}

	/**
	 * Method to set start
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setStart()
	{
	}

	/**
	 * Method to set limit
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function SetLimit()
	{
	}

	/**
	 * Set raw query params.
	 *
	 * @queryParams   Array  $queryParams Array of Key-value pairs to add to query
	 *
	 * @since   12.1
	 */
	public function setRawQueryParams($queryParams)
	{
	}
}
