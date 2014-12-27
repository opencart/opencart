<?php namespace App\Eloquent;

class Language extends EncapsulatedEloquentBase
{
	protected $table = 'language';
	protected $primaryKey = 'language_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

}
