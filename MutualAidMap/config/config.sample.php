<?php
if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_USER')) define('DB_USER', 'your_db_user');
if (!defined('DB_PASS')) define('DB_PASS', 'your_db_pass');
if (!defined('DB_NAME')) define('DB_NAME', 'your_db_name');
if (!defined('GOOGLE_API_KEY')) define('GOOGLE_API_KEY', '');
if (!defined('MAPBOX_KEY')) define('MAPBOX_KEY', 'your_mapbox_token');

// Airtable Configuration
// API Key from Airtable account
if (!defined('AIRTABLE_KEY')) define('AIRTABLE_KEY', 'your_airtable_pat');

// Base ID (e.g. app...)
if (!defined('COVID_DOC')) define('COVID_DOC', 'appf8AVgep0UHZGBw'); // Example Base ID

// Table IDs for Mutual Aid Groups
// Check URL: https://airtable.com/[BaseID]/[TableID]/[ViewID]
if (!defined('COVID_SHEET_GR')) define('COVID_SHEET_GR', 'tblSAotCxT28qpz5C'); // Groups Table
if (!defined('COVID_VIEW_GR')) define('COVID_VIEW_GR', 'viwqogIOLqHB90Ato'); // Groups View
if (!defined('COVID_SHEET_NEIB')) define('COVID_SHEET_NEIB', 'tblA0o2vY5XO5MBpP'); // Neighborhoods Table

// Unused but defined in original
if (!defined('SUBMISSIONS_DOC')) define('SUBMISSIONS_DOC', '');
if (!defined('ASSEMBLIES_DOC')) define('ASSEMBLIES_DOC', '');
if (!defined('COVID_SHEET_USR')) define('COVID_SHEET_USR', '');
if (!defined('ASSEMBLIES_NTA_SHEET')) define('ASSEMBLIES_NTA_SHEET', '');
