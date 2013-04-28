<?php
class ModelSettingExtension extends Model 
{
	protected static $_cache_extensions; // Caching query results
	
	function getExtensions($type, $reload=false) 
	{
		if (!isset(self::$_cache_extensions[$type]) or $reload) 
		{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
			self::$_cache_extensions[$type] = $query->rows;
		}
		return self::$_cache_extensions[$type];
	}
}
