<?php
namespace App\SearchModels;

class FakePlateSearchModel extends BaseSearchModel {

	public $customerId;
  public $customerUserId;
  public $asciiPlateNumber;

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
            $data = ($this->isGuid($value)) ? $value : '';
            break;
          case 'customerid':
            $data = ($this->isGuid($value)) ? $value : '';
            break;
          case 'asciiplatenumber':
            $data = (preg_match('/^[a-zA-Z0-9-]+$/', $value)) ? $value : null;
            break;
          default:
            $data = $value;
            break;
        }
       
        return $data;
    }

  	private function setDefaultSort()  
    {
        $this->defaultSortField = "destructionDateTime";
        $this->defaultSortOrder = "desc";
    }

  	public function getSqlQuery() {
  		return $this->getSql();
  	}

    public function getSortSql() {
      return $this->sortingQuery();  
    }

    function prepareLppmSearchModel() {
        $model = new LppmFakePlateSearchModel();
        $model->customerId = $this->customerId;
        $model->customerUserId = $this->customerUserId;
        $model->asciiPlateNumber = $this->asciiPlateNumber;
        $model->from = ($this->pageIndex-1) * $this->pageSize;
        $model->to = ($this->pageIndex * $this->pageSize);
        $model->sortDir = ($this->sortOrder == "ASC") ? 1 : -1;
        $model->sortCol = $this->sortField;
        $model->defaultSortCol = $this->defaultSortField;
        $model->defaultSortDir = ($this->defaultSortOrder == "ASC") ? 1 : -1;
        return $model;
    }
}