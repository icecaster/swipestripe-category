<?php

class ProductCategory extends Page {

	public static $singular_name = 'Product Category';
	public static $plural_name = 'Product Categories';

	static $db = array(
		"Bla" => "Varchar"
	);

	public static $has_many = array(
		'Products' => 'Product'
	);
	
	public static $summary_fields = array(
		'Title' => 'Name',
		'MenuTitle' => 'Menu Title'
	);
		
}


class ProductCategory_Controller extends Page_Controller {

	public static $products_per_page = 15;

	public function Products() {
		return PaginatedList::create($this->data()->Products(), $this->request)
			->setPageLength($this->config()->get("products_per_page"));
	}
}

class ProductCategory_ProductExtension extends DataExtension {

	public static $has_one = array(    
		"ProductCategory" => "ProductCategory"
	);

	public static $searchable_fields = array(
		'ProductCategoryID' => array(
			'field' => 'ProductCategory_SelectionField',
			'filter' => 'ExactMatchFilter',
			'title' => 'Category'
		)
	);

	public static $summary_fields = array(
		'ProductCategory.Title' => 'Category'
	);

	public function onBeforeWrite() {
		$parent = $this->owner->getParent();
		if ($parent && $parent instanceof ProductCategory) {
			$this->owner->ProductCategoryID = $this->owner->ParentID;
		}
	}
}


class ProductCategory_SelectionField extends GroupedDropdownField {

	function __construct($name, $title = null, $source = array(), $value = '', $form = null, $emptyString = false) {
		parent::__construct($name, $title, $source, $value, $form, $emptyString);
		$categories = ProductCategory::get()->map("ID","Title")->toArray();
		$this->setSource($categories);
		$this->setEmptyString("select a category");
	}

}