<?php namespace App\Eloquent;

class CustomerLogin extends EncapsulatedEloquentBase
{
	protected $table = 'customer_login';
	protected $primaryKey = 'customer_login_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array('email','ip','total');
}