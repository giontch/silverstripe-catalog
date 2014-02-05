<?php

/**
 * @package cms
 */
class CategoryAdminController extends CategoryAdmin {
	
	static $url_segment = 'categories';
	static $url_rule = '/$Action/$ID/$OtherID';
	static $url_priority = 40;
	static $menu_title = 'Category';	
	//static $required_permission_codes = 'CMS_ACCESS_CMSMain';
	static $session_namespace = 'CategoryAdmin';

	public function LinkPreview() {
		return false;
	}

	/**
	 * @return String
	 */
	public function ViewState() {
		return $this->request->getVar('view');
	}

	public function isCurrentPage(DataObject $record) {
		return false;
	}

	public function Breadcrumbs($unlinked = false) {
		$items = parent::Breadcrumbs($unlinked);

		//special case for building the breadcrumbs when calling the listchildren Pages ListView action
		if($parentID = $this->request->getVar('ParentID')) {
			$page = DataObject::get_by_id('SiteTree', $parentID);

			//build a reversed list of the parent tree
			$pages = array();
			while($page) {
				array_unshift($pages, $page); //add to start of array so that array is in reverse order
				$page = $page->Parent;
			}

			//turns the title and link of the breadcrumbs into template-friendly variables
			$params = array_filter(array(
				'view' => $this->request->getVar('view'),
				'q' => $this->request->getVar('q')
			));
			foreach($pages as $page) {
				$params['ParentID'] = $page->ID;
				$item = new StdClass();
				$item->Title = $page->Title;
				$item->Link = Controller::join_links($this->Link(), '?' . http_build_query($params));
				$items->push(new ArrayData($item));
			}
		}

		return $items;

	}
}
