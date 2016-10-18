<?php
/**
 * @package    BiBundle\Service\Backend\Gateway
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Service\Backend\Gateway;


class UrlOptions
{
    /**
     * List of data sources
     */
    const DATA_SOURCES_URL = "/datasources";
    /**
     * Data source info
     */
    const DATA_SOURCES_ITEM_URL = "/datasources/%d";
    /**
     * List of tables in data source
     */
    const DATA_SOURCES_TABLES_URL = "/datasources/%d/tables";
    /**
     * Table info in data source
     */
    const DATA_SOURCES_TABLE_INFO_URL = "/datasources/%d/%s";
    /**
     * Preview data from table in data source
     */
    const DATA_SOURCES_TABLE_PREVIEW_URL = "/datasources/%d/%s/preview";
    /**
     * Create tree on selected card
     */
    const CARDS_TREE_CREATE_URL = "/cubes/%d/nodes";
    /**
     * Load data on selected card
     */
    const CARDS_LOAD_DATA_URL = "/cubes/%d/data";
    /**
     * Get card filters
     */
    const CARDS_FILTERS_URL = "/cubes/%d/get_filters";
    /**
     * Send query to card
     */
    const CARDS_QUERY_URL = "/cubes/%d/query";

    const CUBE_CREATE_URL = '/api/v1/cubes/';
}