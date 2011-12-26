<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This is the categories-action, it will display the overview of events categories
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 */
class BackendEventsCategories extends BackendBaseActionIndex
{
	/**
	 * Execute the action
	 */
	public function execute()
	{
		parent::execute();
		$this->loadDataGrid();
		$this->parse();
		$this->display();
	}

	/**
	 * Loads the datagrids
	 */
	private function loadDataGrid()
	{
		// create datagrid
		$this->dataGrid = new BackendDataGridDB(BackendEventsModel::QRY_DATAGRID_BROWSE_CATEGORIES, BL::getWorkingLanguage());

		// sorting columns
		$this->dataGrid->setSortingColumns(array('title'), 'title');

		// add column
		$this->dataGrid->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_category') . '&amp;id=[id]', BL::lbl('Edit'));

		// row function
		$this->dataGrid->setRowFunction(array('BackendEventsCategories', 'setDefault'), array('[id]'));

		// disable paging
		$this->dataGrid->setPaging(false);

		// add attributes, so the inline editing has all the needed data
		$this->dataGrid->setColumnAttributes('title', array('data-id' => '{id:[id]}'));
	}

	/**
	 * Parse & display the page
	 */
	private function parse()
	{
		$this->tpl->assign('dataGrid', ($this->dataGrid->getNumResults() != 0) ? $this->dataGrid->getContent() : false);
	}

	/**
	 * Set class on row with the default class
	 *
	 * @param int $id The id of the category.
	 * @param array $rowAttributes The current row attributes.
	 * @return array
	 */
	public static function setDefault($id, $rowAttributes)
	{
		// is this the default category?
		if(BackendModel::getModuleSetting('events', 'default_category_' . BL::getWorkingLanguage(), null) == $id)
		{
			// class already defined?
			if(isset($rowAttributes['class'])) $rowAttributes['class'] .= ' isDefault';

			// set class
			else $rowAttributes['class'] = 'isDefault';

			// return
			return $rowAttributes;
		}
	}
}
