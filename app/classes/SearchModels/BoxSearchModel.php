<?php
namespace App\SearchModels;

class BoxSearchModel extends BaseSearchModel {

    public $customerId;

  	function __construct($paramsFromUrl) {
  		parent::__construct();
  		$this->mapToClass($paramsFromUrl);
  		$this->setDefaultSort();
  	}

    private function mapToClass($paramsFromUrl)
    {
        foreach(get_object_vars($this) as $attrName => $attrValue)
          if (isset($paramsFromUrl[$attrName]))
            $this->{$attrName} = $this->sanitize($paramsFromUrl[$attrName], strtolower($attrName));
    }

    function sanitize($value, $attrName) {
        return $value;
    }

  	private function setDefaultSort() 
    {
        $this->defaultSortField = "Date";
        $this->defaultSortOrder = "desc";
    }

  	public function getSqlQuery() {
  		return $this->getSql();
  	}

    public function getSortSql() {
      return $this->sortingQuery();  
    }
}