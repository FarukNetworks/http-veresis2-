<?php
namespace App\SearchModels;
use \PDO;

class OrderSearchModel extends BaseSearchModel {

  	public $customerId;
    public $orderTypeSelection;
    public $orderStateSelection;

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

    function isGuid($id) {
      $guid = new \App\Helpers\GuidGenerator();
      $isParameterOrderIdGuid = $guid->validationGuid($id);
      if (!$isParameterOrderIdGuid) {
          return false;
      }
      return true;
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
        //     //$data = (preg_match('/^[a-zA-Z]+$/', $value)) ? $value : null;
        //     $data = 
        //     break;
        //   case 'sortorder':
        //     $data = (!(empty($value)) && ($value == "ASC" || $value == "DESC")) ? $value : null;
        //     break;
        //   case 'orderstateselection':
        //     $data = (preg_match('/^\d{1}(?:[,]\d{1})*$/m', $value)) ? $value : "";
        //     break;
        //   case 'ordertypeselection':
        //     $data = (preg_match('/^\d{1}(?:[,]\d{1})*$/m', $value)) ? $value : "";
        //     break;
        //   case 'customerid':
        //     $data = ($this->isGuid()) ? $value : '';
        //     break;
        //   default:
        //     $data = $value;
        //     break;
        // }
        return $value;
    }

    private function setDefaultSort() 
    {
        $this->defaultSortField = "OrderDate";
        $this->defaultSortOrder = "desc";
    }

  	public function getSqlQuery() {
  		return $this->getSql();
  	}

    public function getSortSql() {
      return $this->sortingQuery();  
    }
}