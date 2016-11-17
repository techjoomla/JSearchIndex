<?php

/**
 * @version    SVN: <svn_id>
 * @package    Techjoomla.Libraries
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

defined('JPATH_PLATFORM') or die;


require_once JPATH_SITE . '/libraries/jsearchindex/algoliasearch-client-php/algoliasearch.php';

/**
 * JSearchIndex Algolia
 *
 * @package     Libraries
 * @subpackage  JSearchIndex
 * @since       1.0
 */

class JSearchIndexAlgolia extends JSearchIndex
{
	protected $options;

	/**
	 * Constructor.
	 *
	 * @param   array  $options  List of options used to configure the connection
	 *
	 * @since   1.0
	 */
	public function __construct($options)
	{
		$this->options = $options;

		//  Get Algolia details & define object
		$algolia_appid			= $this->options->get('algolia_appid');
		$algolia_searchapi_key	= $this->options->get('algolia_searchapi_key');
		$algolia_indexname		= $this->options->get('algolia_indexname');

		$algolia_ad_api_key		= $this->options->get('algolia_insertionapi_key');

		$this->algoliaClient	= new \AlgoliaSearch\Client($algolia_appid, $algolia_searchapi_key);
		$this->algoliaIndex		= $this->algoliaClient->initIndex($algolia_indexname);

		$this->algoliaADClient	= new \AlgoliaSearch\Client($algolia_appid, $algolia_ad_api_key);
		$this->algoliaADIndex		= $this->algoliaADClient->initIndex($algolia_indexname);

		$this->batchSize		= 2;
		$this->dataSet			= array();

		$this->start			= 0;
		$this->limit			= 20;
		$this->searchPhrase		= "";
		$this->facetField		= '';
		$this->facetFilters		= '';
		$this->records			= '';
	}

	/**
	 * Get all settings related to Algolia.
	 *
	 * @return an array of settings
	 *
	 * @since   1.0
	 */
	public function getSettings()
	{
		return $this->algoliaIndex->getSettings();
	}

	/**
	 * Set all settings related to Algolia.
	 *
	 * @param   Array  $settings  array of settings
	 *
	 * @return  settings Id
	 *
	 * @since   1.0
	 */
	public function setSettings($settings)
	{
		$res = $this->algoliaIndex->setSettings($settings);

		return $this->algoliaIndex->waitTask($res['taskID']);
	}

	/**
	 * Method to Add record on respective data source
	 *
	 * @param   Array  $recordArray  Field Pass multidimensional array of data
	 *
	 * @return  count of inserted records
	 *
	 * @since   1.0
	 */
	public function addRecords($recordArray)
	{
		if (count($recordArray) >= 1)
		{
			$this->dataSet = $recordArray;
		}
	}

	/**
	 * Set columns to retrieve data from query.
	 *
	 * @param   String  $selectFields  Field name
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function selectFields($selectFields)
	{
		$this->select = $selectFields ? $selectFields : '*';
	}

	/**
	 * Get the total number of records in current set.
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function getRecords()
	{
		return $this->records['data'];
	}

	/**
	 * Get the facet records
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function getFacetData()
	{
		return $this->records['facets'];
	}

	/**
	 * Get the data from Algolia.
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		$searchQuery = $this->searchPhrase ? '"' . $this->searchPhrase . '"' : '';
		$queryParams['attributesToRetrieve']	= '*';
		$queryParams['maxValuesPerFacet']		= '100';
		$queryParams['attributesToHighlight']	= array();
		$queryParams['filters']					= $this->filters;
		$queryParams['facetFilters']			= $this->facetFilters;
		$queryParams['offset']					= $this->start;
		$queryParams['length']					= $this->limit;

		$queryParams['facets']					= array($this->facetField);

		// We need to pass this query by setRawQueryParams for advsearch frontent.
		$queryParams['advancedSyntax']			= 'true';

		if (count($this->rawQueryParams))
		{
			$queryParams = array_merge($queryParams, $this->rawQueryParams);
		}

		// This is something Juggad related to Osianama requirement.
		if (isset($queryParams['restrictSearchableAttributes']))
		{
			$searchQuery = $this->searchPhrase ? $this->searchPhrase : '';
		}

		$Data = $this->algoliaIndex->search($searchQuery, $queryParams);

		if (count($Data['hits']) > 0)
		{
			$this->total	= $Data['nbHits'];
			$this->count	= $Data['hitsPerPage'];
			$this->records	= '';

			foreach ($Data['hits'] as $key => $row)
			{
				unset($row['_highlightResult']);

				$row['id']						= $row['objectID'];
				$this->records['data'][$key]	= (object) $row;
			}

			if ($this->facetField)
			{
				$this->records['facets'] = $Data['facets'];
			}
		}
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
		$recordArray = $this->dataSet;

		if (count($recordArray) >= 1)
		{
			// Process data in batch
			$dataBatch	= array();
			$count		= 0;

			foreach ($recordArray as $k => $obj)
			{
				try
				{
					// Push data into array
					array_push($dataBatch, $obj);

					if (count($dataBatch) == $this->batchSize)
					{
						$processData	= $this->algoliaADIndex->saveObjects($dataBatch);
						$dataBatch		= array();
						$count			+= count($processData['objectIDs']);

						foreach ($processData['objectIDs'] as $key => $val)
						{
							$processedData1[] = $val;
						}

						$processedData = $processedData1;
					}
				}
				catch (exception $e)
				{
					die('Error occured while inserting records into Algolia! - ' . $e->getMessage());
				}
			}

			// Insert records in Algolia if record array is less than batchSize
			try
			{
				if (count($dataBatch) < $this->batchSize && count($dataBatch) > 0)
				{
						$processData	= $this->algoliaADIndex->saveObjects($dataBatch);
						$dataBatch		= array();
						$count			+= count($processData['objectIDs']);

						foreach ($processData['objectIDs'] as $key => $val)
						{
							$processedData2[] = $val;
						}

						if (count($processedData1) > 0)
						{
							$processedData = array_merge($processedData1, $processedData2);
						}
						else
						{
							$processedData = $processedData2;
						}
				}
			}
			catch (exception $e)
			{
				return 'Error occured while inserting records into Algolia! - ' . $e->getMessage();
			}

		return $processedData;
		}
		else
		{
			return 0;
		}
	}

	/**
	 *  Method to delete on respective data source
	 *
	 * @param   Array  $objectArray  Array of record Id's
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function deleteRecords($objectArray)
	{
		if (count($objectArray) >= 1)
		{
			// Process data in batch
			$dataBatch	= array();
			$count		= 0;

			foreach ($objectArray as $objId)
			{
				try
				{
					array_push($dataBatch, $objId);

					if (count($dataBatch) == $this->batchSize)
					{
						$deletedData = $this->algoliaADIndex->deleteObjects($dataBatch);

						$dataBatch		= array();
						$count			+= count($deletedData['objectIDs']);
					}
				}
				catch (exception $e)
				{
					print_r($e);
					die('Error occured while deleting records into Algolia! - ' . $e->getMessage());
				}
			}

			// Insert records in Algolia if record array is less than batchSize
			try
			{
				if (count($dataBatch) < $this->batchSize && count($dataBatch) > 0)
				{
					$deletedData = $this->algoliaADIndex->deleteObjects($dataBatch);
					$dataBatch		= array();
					$count			+= count($deletedData['objectIDs']);
				}
			}
			catch (exception $e)
			{
				die('Error occured while inserting records into Algolia! - ' . $e->getMessage());
			}

		return $count;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Method to delete Algolia data index
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function clearRecords()
	{
		die('Amol. here!!');
	}

	/**
	 * Method to Add the search phrase, when you’re searching for specific search phrase Default is empty
	 *
	 * @param   String  $searchPhrase  Search term
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setSearchWord($searchPhrase)
	{
		$this->searchPhrase	= $searchPhrase;
	}

	/**
	 * Method to set facet field. If set returns facet data along with records.
	 *
	 * @param   Array  $facetField  Array of column names(fields)
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setFacetField($facetField)
	{
		if ($facetField)
		{
			$this->facetField	= $facetField;
		}
	}

	/**
	 * Method to get records based on filters
	 *
	 * @param   Array  $arrayofFieldValues  Array of column names
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setFilters($arrayofFieldValues)
	{
		$i = 0;
		$this->filters = '';

		foreach ($arrayofFieldValues as $field => $value)
		{
			if ($i == count($arrayofFieldValues) - 1)
			{
				$this->filters .= "$field:$value";
			}
			else
			{
				$this->filters .= "$field:$value AND ";
			}

			$i++;
		}
	}

	/**
	 * Method to set Facets
	 *
	 * @param   Array  $arrayofFieldValues  Array of column names
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setFacetFilters($arrayofFieldValues)
	{
		$i = 0;

		foreach ($arrayofFieldValues as $field => $value)
		{
			$this->facetFilters .= ($i == count($arrayofFieldValues) - 1) ? "$field:$value" : "$field:$value , ";
			$i++;
		}
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
		return $this->count;
	}

	/**
	 * Method to get count of records
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function getTotal()
	{
		return $this->total;
	}

	/**
	 * Method to set start
	 *
	 * @param   Integer  $start  Pass the start limit to get data
	 *
	 * @return  true/false
	 *
	 * @since   1.0
	 */
	public function setStart($start)
	{
		$this->start		= $start;
	}

	/**
	 * Setlimit query
	 *
	 * @param   Integer  $setLimit  Pass the limit to get data
	 *
	 * @return nothing
	 *
	 * @since   12.1
	 */
	public function setLimit($setLimit)
	{
		$this->limit		= $setLimit;
	}

	/**
	 * Set raw query params.
	 *
	 * @param   String  $queryParams  Array of Key-value pairs to add to query
	 *
	 * @return nothing
	 *
	 * @since   12.1
	 */
	public function setRawQueryParams($queryParams)
	{
		$this->rawQueryParams		= $queryParams;
	}
}
