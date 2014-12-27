<?php namespace App\Eloquent;

class Information extends EncapsulatedEloquentBase
{
	protected $table = 'information';
	protected $primaryKey = 'information_id';
	// protected $translatable = array('title', 'description', 'meta_title', 'meta_description', 'meta_keyword');
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	public function translation()
	{
		return $this->belongsToMany('App\Eloquent\Language', 'information_description')->withPivot('title', 'description', 'meta_title', 'meta_description', 'meta_keyword');
	} 
	public function setLanguage($language_id)
	{
		$translation = array('title', 'description', 'meta_title', 'meta_description', 'meta_keyword');
		foreach ($translation as $value) {
			$this->{$value} = $this->translation->find($language_id)->pivot->{$value};
		}
	} 
}
