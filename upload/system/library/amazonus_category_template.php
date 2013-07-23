<?php
class Amazonus_category_template {
    
    private $simpleXML;

    public function load($data) {
        if(($this->simpleXML = simplexml_load_string($data)) == false) {
            return false;
        } else { 
            return true;
        }
    }
    
    public function getRequiredFields() {
        return $this->getFields("required");
    }
    
     public function getDesiredFields() {
        return $this->getFields("desired");
    }   
    
    public function getOptionalFields() {
        return $this->getFields("optional");
    }
    
    public function getTabs() {
        $tabs = array();
        foreach($this->simpleXML->tabs->tab as $tab) {
            $attributes = $tab->attributes();
            $tabs[] = array(
                'id' => (string)$attributes['id'],
                'name' => (string)$tab->name,
            );
        }
        return $tabs;
    }
    
    public function getAllFields() {
        $merged = array_merge($this->getRequiredFields(), $this->getDesiredFields(), $this->getOptionalFields());
        
        foreach($merged as $index => $field) {
            $merged[$index]['unordered_index'] = $index;
        }
        usort($merged, array('Amazonus_category_template','compareFields'));
        return $merged;
    } 
    
    public function getCategoryName() {
        return (string)$this->simpleXML->filename;
    }
    
    private function getFields($name) {
        $fields = array();
        
        foreach($this->simpleXML->fields->$name->field as $field) {
            $attributes = $field->attributes();
            $fields[] = array(
                'name' => (string)$attributes['name'], 
                'title' => (string)$field->title,
                'definition' => (string)$field->definition,
                'accepted' => (array)$field->accepted,
                'type' => (string)$name,
                'child' => false,
                'order' => isset($attributes['order']) ? (string)$attributes['order'] : '',
                'tab' => (string)$attributes['tab'], 
                );
        }
        foreach($this->simpleXML->fields->$name->childfield as $field) {
            $attributes = $field->attributes();
            $fields[] = array(
                'name' => (string)$attributes['name'],
                'title' => (string)$field->title,
                'definition' => (string)$field->definition,
                'accepted' => (array)$field->accepted,
                'type' => (string)$name,
                'child' => true,
                'parent' => (array)$field->parent,
                'order' => isset($attributes['order']) ? (string)$attributes['order'] : '',
                'tab' => (string)$attributes['tab'],
                );
        }
        
        return $fields;
    }
    
    //Used to sort fields array
    private static function compareFields($field1, $field2) {
        if($field1['order'] == $field2['order']) {
            return ($field1['unordered_index'] < $field2['unordered_index']) ? -1 : 1;
        } else if(!empty($field1['order']) && empty($field2['order'])) {
            return -1;
        } else if(!empty($field2['order']) && empty($field1['order'])) {
            return 1;
        } else {
            return ($field1['order'] < $field2['order']) ? -1 : 1;
        }
    }
}