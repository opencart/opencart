<?php namespace App\Eloquent;

class CustomerActivity extends EncapsulatedEloquentBase
{
	protected $table = 'customer_activity';
	protected $primaryKey = 'activity_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array();
}