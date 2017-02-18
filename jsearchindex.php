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
abstract class JSearchIndex
{
	/**
	 * Method to initialise class based on global setting in Advanced Search
	 *
	 * @return  object of solr or algolia default is sql
	 *
	 * @since   1.0
	 */
	public static function init(JRegistry $options = null)
	{

		$app  = JFactory::getApplication();


		$advparams = $options ? $options : $app->getParams('com_advsearch');
		$adaptor = $advparams->get('adaptor', 'sql');
		$adaptorclass = 'JSearchIndex' . ucfirst(strtolower($adaptor));

		if (!class_exists($adaptorclass))
		{
			throw new Exception('This data source not supported');
		}

		return new $adaptorclass($advparams);
	}

	/**
	 * Method to Add record on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function addRecords($recordArrary);

	/**
	 * Method to Get record on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function getRecords();

	/**
	 * Execute the search/browse query
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function execute();

	/**
	 * Get the total number of records in current set.
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function getCount();

	/**
	 * Method to Set ID
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setId();

	/**
	 * Method to AddSeparator
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	abstract public function addSeparator();

	/**
	 * Method to commit files to respective data source i.e. once an addRecords operation is done
	 * call this method to commit the records to the data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function commit();

	/**
	 * Method to delete on respective data source
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function deleteRecords($objectArray);

	/**
	 * Highly destructive. Method to delete all records from the data index
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function clearRecords();

	/**
	 * Method to set the search phrase for a search query.  empty
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setSearchWord($searchPhrase);

	/**
	 * Method to set filters for search
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setFilters($arrayofFieldValues);

	/**
	 * Method to set Facet field name
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setFacetField($facetField);

	/**
	 * Method to set Facets
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setFacetFilters($arrayofFieldValues);

	/**
	 * Method to set startlimit for the results
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setStart($start);

	/**
	 * Get total count of record that match the search
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function getTotal();

	/**
	 * Method to set the limit for number of records to retrieve
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setLimit($setLimit);

	/**
	 * Method to set fields that need to be returned in the response
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function selectFields($selectFields);

	/**
	 * Method to set raw query params
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	abstract public function setRawQueryParams($queryParams);
}
