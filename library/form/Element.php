<?php

	abstract class Element extends Base
	{
		private $errors = array();
	
		protected $attributes;
		protected $form;
		protected $label;
		protected $position;
		protected $errorul;
		protected $description;
		protected $validation = array();
	
		public function __construct($label, $name, $position = 'left', array $properties = null, $errorul = '')
		{
			$this->position	= $position;
			$this->errorul	= $errorul;
			$configuration	= array(
				"label" => $label,
				"name" => $name,
			);
			/*
			 * Merge any properties provided with an associative array containing
			 * the label and name properties
			 * */
			if(is_array($properties))
				$configuration = array_merge($configuration, $properties);
	
			$this->configure($configuration);
		}
	
		/*When an element is serialized and stored in the session, this method prevents any non-essential
		information from being included.*/
		public function __sleep()
		{
			return array("attributes", "label", "validation");
		}
	
		/*If an element requires external stylesheets, this method is used to return an
		array of entries that will be applied before the form is rendered.*/
		public function getCSSFiles()
		{
			
		}
	
		public function getDescription()
		{
			return $this->description;
		}
	
		public function getErrors()
		{
			return $this->errors;
		}
	
		public function getID()
		{
			if(!empty($this->attributes['id'])) return $this->attributes['id'];
			else return '';
		}
	
		/*If an element requires external javascript file, this method is used to return an
		array of entries that will be applied after the form is rendered.*/
		public function getJSFiles()
		{
			
		}
	
		public function getLabel()
		{
			return $this->label;
		}
	
		public function getElemPos()
		{
			return ($this->position) ? $this->position : 'left';
		}
	
		public function getErrorUl()
		{
			return ($this->errorul) ? $this->errorul : '';
		}
	
		public function getName()
		{
			if(!empty($this->attributes['name'])) return $this->attributes['name'];
			else return '';
		}
	
		/*This method provides a shortcut for checking if an element is required.*/
		public function isRequired()
		{
			if(!empty($this->validation))
			{
				foreach($this->validation as $validation)
				{
					if($validation instanceof Validation_Required) return true;
				}
			}
			
			return false;
		}
	
		/*The isValid method ensures that the provided value satisfies each of the
		element's validation rules.*/
		public function isValid($value)
		{
			$valid = true;
			if(!empty($this->validation))
			{
				if(!empty($this->label))
				{
					$element = $this->label;
					if(substr($element, -1) == ':') $element = substr($element, 0, -1);
				}
				else $element = $this->attributes['name'];
	
				foreach($this->validation as $validation)
				{
					if(!$validation->isValid($value))
					{
						/*In the error message, %element% will be replaced by the element's label (or
						name if label is not provided).*/
						$this->errors[] = str_replace("%element%", $element, $validation->getMessage());
						$valid = false;
					}
				}
			}
			
			return $valid;
		}
	
		public function setForm(Form $form)
		{
			$this->form = $form;
		}
	
		public function setID($id)
		{
			$this->attributes["id"] = $id;
		}
	
		public function setValue($value)
		{
			$this->attributes["value"] = $value;
		}
	
		/*This method provides a shortcut for applying the Required validation class to an element.*/
		public function setRequired($required)
		{
			if(!empty($required)) $this->validation[] = new Validation_Required;
		}
	
		/*This method applies one or more validation rules to an element.  If can accept a single concrete
		validation class or an array of entries.*/
		public function setValidation($validation)
		{
			/*If a single validation class is provided, an array is created in order to reuse the same logic.*/
			if(!is_array($validation)) $validation = array($validation);
			foreach($validation as $object)
			{
				/*Ensures $object contains a existing concrete validation class.*/
				if($object instanceof Validation) $this->validation[] = $object;
			}
		}
	
		/*Many of the included elements make use of the <input> tag for display.  These include the Hidden, Textbox,
		Password, Date, Color, Button, Email, and File element classes.  The project's other element classes will
		override this method with their own implementation.*/
		public function render()
		{
			echo '<input', $this->getAttributes(), '/>';
		}
	}
