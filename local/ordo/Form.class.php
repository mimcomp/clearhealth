<?php
/**
 * Object Relational Persistence Mapping Class for table: form
 *
 * @package	com.uversainc.clearhealth
 * @author	Joshua Eichorn <jeichorn@mail.com>
 */

/**#@+
 * Required Libs
 */
$loader->requireOnce('includes/Datasource_sql.class.php');
/**#@-*/

/**
 * Object Relational Persistence Mapping Class for table: form
 *
 * @package	com.uversainc.clearhealth
 */
class Form extends ORDataObject {

	/**#@+
	 * Fields of table: form mapped to class members
	 */
	var $id			= '';
	var $name		= '';
	var $description	= '';
	var $system_name	= '';
	
	/**#@-*/

	var $_table = 'form';
	var $_internalName='Form';

	/**
	 * Setup some basic attributes
	 * Shouldn't be called directly by the user, user the factory method on ORDataObject
	 */
	function Form($db = null) {
		parent::ORDataObject($db);	
		$this->_sequence_name = 'sequences';	
	}

	/**
	 * Called by factory with passed in parameters, you can specify the primary_key of Form with this
	 */
	function setup($id = 0) {
		if ($id > 0) {
			$this->set('id',$id);
			$this->populate();
		}
	}

	/**
	 * Populate the class from the db
	 */
	function populate() {
		parent::populate('form_id');
	}

	/**
	 * Get a ds with data about all forms
	 */
	function &formList() {
		$ds =& new Datasource_sql();
		$ds->setup($this->_db,array(
				'cols' 	=> "f.form_id, f.name, f.description, f.system_name",
				'from' 	=> "$this->_table f",
				'orderby' => 'name',
			),
			array('name'=>'Name','description'=>'Description','system_name'=>'System Name'));
		return $ds;
	}

	/**
	 * Get a simple form list
	 */
	function simpleFormList($objects = false) {
		$res = $this->_execute("select form_id, name from $this->_table order by name");

		$ret = array();

		while($res && !$res->EOF) {
			if ($objects) {
				unset($o);
				$o = new stdClass();
				foreach($res->fields as $key => $val) {
					$o->$key = $val;
				}
				$ret[] = $o;
			}
			else {
				$ret[$res->fields['form_id']] = $res->fields['name'];
			}
			$res->moveNext();
		}
		return $ret;
	}

	/**#@+
	 * Getters and Setters for Table: form
	 */

	
	/**
	 * Getter for Primary Key: form_id
	 */
	function get_form_id() {
		return $this->id;
	}

	/**
	 * Setter for Primary Key: form_id
	 */
	function set_form_id($id)  {
		$this->id = $id;
	}

	/**
	 * Getter for the full form path
	 */
	function get_file_path() {
		$config =& Celini::ConfigInstance();
		$forms_dir = $config->get('user_forms_dir',APP_ROOT."/user/form/");
		return $forms_dir.$this->get('id').".tpl.html";
	}

	/**#@-*/

	/**
	 * Does this form have a file uploaded for it
	 */
	function fileExists() {
		return file_exists($this->get('file_path'));
	}


}
?>
