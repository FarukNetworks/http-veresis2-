<?php
namespace App\SearchModels;

class BaseSearchModel
{
	public $pageIndex;
    public $pageSize;
    public $sortField;
    public $sortOrder;
    public $defaultSortField;
    public $defaultSortOrder;
    public $customOrder;

	function __construct() {

	}

	function getSql() {
		$sortQuery = $this->sortingQuery();
		$paging = $this->getPagingQuery();
		return (!empty($sortQuery)) ? array_merge($sortQuery, $paging) : $paging;		
	}

	public function sortingQuery() {
		if (!empty($this->customOrder)) {
			return ["sortsql" =>" ORDER BY {$this->customOrder}"];
		}
		if (!(empty($this->sortField)) && !(empty($this->sortOrder))) {
			 $data = ($this->sortOrder == "ASC" || $this->sortOrder == "DESC") ? $this->sortOrder : "ASC";
			 $field = preg_replace('/[^a-zA-Z_]/', "", $this->sortField);
			 return [ "sortsql" => " ORDER BY `{$field}` {$data}" ];			  	
		}
		if (!empty($this->defaultSortField) && !empty($this->defaultSortOrder)) {
			return ["sortsql" => " ORDER BY `{$this->defaultSortField}` {$this->defaultSortOrder}"];
		}		
	}

	public function getPagingQuery() {
		$pageInt = (!empty($this->pageIndex)) ? intval($this->pageIndex) : 0;
		$pageSize = (!empty($this->pageSize)) ? intval($this->pageSize) : 0;

		$pageIndex = ($pageInt <=1) ? 1 : $pageInt;

		if ($pageInt == 0 || $pageSize == 0) {
			$calcultePage = 0;
		} else {
			$calcultePage = $pageInt * $pageSize - $pageSize;	
		}
		
		return [ "pagingsql" => " LIMIT ?, ?", "pagingparams" => array($calcultePage,$pageSize)];
	}	
}