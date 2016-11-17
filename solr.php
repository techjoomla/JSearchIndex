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
class JSearchIndexSolr extends JSearchIndex
{
	/**
	 * Method to Add record on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function addRecord()
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
	public function setId()
	{
	}

	/**
	 * Method to  AddSeparator
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function addSeparator()
	{
	}

	/**
	 * Method to  commit files to  respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function commit()
	{
	}

	/**
	 *  Method to delete on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function deleteRecord()
	{
	}

	/**
	 * Method to delete algolia or solr or any other
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function clearRecords()
	{
	}

	/**
	 * Method to Add the search phrase, when you’re searching for specific search phrase Default is empty
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setSearchWord()
	{
	}

	/**
	 * Method to get records based on filters
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setFilters()
	{
	}

	/**
	 * Method to set Facets
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setFacets()
	{
	}

	/**
	 * Method to get count of records
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function getCount()
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
	public function setLimit()
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
