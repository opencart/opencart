<?php namespace App\Eloquent;

class Address extends EncapsulatedEloquentBase
{
	protected $table = 'address';
	protected $primaryKey = 'address_id';
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_modified';

	protected $fillable = array('firstname','lastname','company','address_1','address_2','city','postcode','country_id','zone_id');

	public function user()
	{
		return $this->belongsTo('App\Eloquent\User');
	}
	public function zone()
	{
		return $this->belongsTo('App\Eloquent\Zone');
	}
	public function country()
	{
		return $this->belongsTo('App\Eloquent\Country');
	}

	public function getData()
	{
		return array(
			'address_id'     => $this->address_id,
			'firstname'      => $this->firstname,
			'lastname'       => $this->lastname,
			'company'        => $this->company,
			'address_1'      => $this->address_1,
			'address_2'      => $this->address_2,
			'postcode'       => $this->postcode,
			'city'           => $this->city,
			'zone_id'        => $this->zone_id,
			'zone'           => $this->zone->name,
			'zone_code'      => $this->zone->code,
			'country_id'     => $this->country_id,
			'country'        => $this->country->name,
			'iso_code_2'     => $this->country->iso_code_2,
			'iso_code_3'     => $this->country->iso_code_3,
			'address_format' => $this->country->address_format,
			'custom_field'   => $this->custom_field
		);
	}
}
