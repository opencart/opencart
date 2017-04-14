<?php namespace App\Eloquent;

class Zone extends EncapsulatedEloquentBase
{
	protected $table = 'zone';
	protected $primaryKey = 'zone_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

}
