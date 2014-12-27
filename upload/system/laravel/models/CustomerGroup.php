<?php namespace App\Eloquent;

class CustomerGroup extends EncapsulatedEloquentBase
{
	protected $table = 'customer_group';
	protected $primaryKey = 'customer_group_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';
}