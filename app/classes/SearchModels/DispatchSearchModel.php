<?php
namespace App\SearchModels;

class DispatchSearchModel extends BaseSearchModel {

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
        // $data = "";
        // switch ($attrName) {
        //   case 'pagesize':
        //     $data = (preg_match('/^[0-9]*$/', $value)) ? intval($value) : 0;
        //     break;
        //   case 'pageindex':
        //     $data = (preg_match('/^[0-9]*$/', $value)) ? intval($value) : 0;
        //     break;
        //   case 'sortfield':
        //     $data = (preg_match('/^[a-zA-Z]+$/', $value)) ? $value : null;
        //     break;
        //   case 'sortorder':
        //     $data = (!(empty($value)) && ($value == "ASC" || $value == "DESC")) ? $value : null;
        //     break;
        //   default:
        //     $data = $value;
        //     break;
        // }
       
        return $value;
    }

  	private function setDefaultSort() 
    {
        $this->defaultSortField = "DispatchDate";
        $this->defaultSortOrder = "desc";
    }

  	public function getSqlQuery() {
  		return $this->getSql();
  	}

    public function getSortSql() {
      return $this->sortingQuery();  
    }


}