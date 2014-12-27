<?php namespace App\Eloquent;

class Country extends EncapsulatedEloquentBase
{
	protected $table = 'country';
	protected $primaryKey = 'country_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

}
