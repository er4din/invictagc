<?php
class PM_Helper_PROFILE_VISITOR_DETAILS{

	public function get_db_table_name($identifier)
	{
		global $wpdb;
		$plugin_prefix = $wpdb->prefix.'promag_';
		$table_name= $plugin_prefix."profile_visitor_details";
		return $table_name;
	}
	
	public function get_db_table_unique_field_name($identifier)
	{
	   $unique_field_name = 'id';
		return $unique_field_name;
	}	
	
	public function get_db_table_field_type($identifier,$field)
	{
		switch ($field)
		{
			case 'id':
				$format = '%d';
				break;
			case 'privacy':
				$format = '%d';
				break;
			case 'uid':
				$format = '%d';
				break;
			default:
				$format = '%s';
		}
		return $format;
	}
	
	
	public function get_sanitized_fields($identifier,$field,$value)
	{
		switch($field)
		{
			case 'id':
				$sanitized_value = sanitize_text_field($value);
				break;
			case 'uid':
				$sanitized_value = sanitize_text_field($value);
				break;
			case 'privacy':
				$sanitized_value = sanitize_text_field($value);
				break;
			case 'album_cover':
				$sanitized_value = sanitize_text_field($value);
				break;
			default:
				$sanitized_value = sanitize_text_field($value);
		}
		
		return $sanitized_value;
	}
	
       
}

?>