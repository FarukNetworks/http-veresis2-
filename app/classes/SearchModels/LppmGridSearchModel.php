<?php
namespace App\SearchModels;

class LppmGridSearchModel {
	public $from;
    public $to;
    public $sortDir;
    public $sortCol;
    public $defaultSortCol;
    public $defaultSortDir;

    function __construct() {

    }
}

class LppmPacksSearchmodel extends LppmGridSearchModel {
	public $customerId;
	public $customerUserId;

	function __construct() {
    	parent::__construct();	
    }
}

class LppmPackDetailSearchModel extends LppmGridSearchModel {
    public $id;
    public $customerId;

    function __construct() {
        parent::__construct();  
    }
}

class LppmFakePlateSearchModel extends LppmGridSearchModel {
    public $customerId;
    public $customerUserId;
    public $asciiPlateNumber;

    function __construct() {
        parent::__construct();  
    }
}