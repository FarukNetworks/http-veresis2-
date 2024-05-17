<?php
namespace App\SearchModels;

class PackSearchModel extends BaseSearchModel {

	public $customerId;
  public $customerUserId; 
 
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
      } else {
          return true;
      }
      
    }

    function sanitize($value, $attrName) {
        $data = "";
        switch ($attrName) {
          case 'pagesize':
            $data = (preg_match('/^[0-9]*$/', $value)) ? intval($value) : 20;
            break;
          case 'pageindex':
            $data = (preg_match('/^[0-9]*$/', $value)) ? intval($value) : 1;
            break;
          case 'sortfield':
            $data = (preg_match('/^[a-zA-Z_]+$/', $value)) ? $value : null;
            break;
          case 'sortorder':
            $data = (!(empty($value)) && ($value == "ASC" || $value == "DESC")) ? $value : null;
            break;
          case 'customeruserid':
            $data = ($this->isGuid($value)) ? $value : "";
            break;
          case 'customerid':
            $data = ($this->isGuid($value)) ? $value : "";
            break;
          default:
            $data = $value;
            break;
        }
       
        return $data;
    }

  	private function setDefaultSort() 
    {
        $this->defaultSortField = "serialNumber";
        $this->defaultSortOrder = "desc";
    }

  	public function getSqlQuery() {
  		return $this->getSql();
  	}

    public function getSortSql() {
      return $this->sortingQuery();  
    }

    function prepareLppmSearchModel() {
        $model = new LppmPacksSearchmodel();
        $model->customerId = $this->customerId;
        $model->customerUserId = $this->customerUserId;
        $model->from = ($this->pageIndex-1) * $this->pageSize;
        $model->to = ($this->pageIndex * $this->pageSize);
        $model->sortDir = (empty($this->sortField)) ? 0 : (($this->sortOrder == "ASC") ? 1 : -1);
        $model->sortCol = (empty($this->sortField)) ? $this->defaultSortField : "";
        $model->defaultSortCol = $this->defaultSortField;
        $model->defaultSortDir = ($this->defaultSortOrder == "ASC") ? 1 : -1;
        return $model;
    }
}

class PackDetailSearchModel extends BaseSearchModel {
    public $id;
    public $customerId;

    function __construct($paramsFromUrl) {
      parent::__construct();
      $this->mapToClass($paramsFromUrl);
      $this->setDefaultSort();
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;  
    }

    private function setDefaultSort() 
    {
        $this->defaultSortField = "asciiPlateNumber";
        $this->defaultSortOrder = "asc";
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
      } else {
          return true;
      }
      
    }

    function sanitize($value, $attrName) {
        $data = "";
        switch ($attrName) {
          case 'pagesize':
            $data = (preg_match('/^[0-9]*$/', $value)) ? intval($value) : 20;
            break;
          case 'pageindex':
            $data = (preg_match('/^[0-9]*$/', $value)) ? intval($value) : 1;
            break;
          case 'sortfield':
            $data = (preg_match('/^[a-zA-Z]+$/', $value)) ? $value : null;
            break;
          case 'sortorder':
            $data = (!(empty($value)) && ($value == "ASC" || $value == "DESC")) ? $value : null;
            break;
          case 'id':
            $data = ($this->isGuid($value)) ? $value : '';
            break;          
          default:
            $data = $value;
            break;
        }
       
        return $data;
    }

    function prepareLppmSearchModel() {
        $model = new LppmPackDetailSearchModel();
        $model->id = $this->id;
        $model->customerId = $this->customerId;
        $model->from = ($this->pageIndex-1) * $this->pageSize;
        $model->to = ($this->pageIndex * $this->pageSize);
        $model->sortDir = ($this->sortOrder == "ASC") ? 1 : -1;
        $model->sortCol = $this->sortField;
        $model->defaultSortCol = $this->defaultSortField;
        $model->defaultSortDir = ($this->defaultSortOrder == "ASC") ? 1 : -1;
        //var_dump($model);
        return $model;
    }  
} 