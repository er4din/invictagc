<?php
class PM_Helper_CUSTOMTABS{

	public function get_db_table_name($identifier)
	{
		global $wpdb;
		$plugin_prefix = $wpdb->prefix.'promag_';
		$table_name= $plugin_prefix."customtabs";
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
			case 'tab_label':
				$sanitized_value = sanitize_text_field($value);
				break;
			case 'tab_data_type':
				$sanitized_value = sanitize_text_field($value);
				break;
                        case 'tab_meta':
				$sanitized_value = sanitize_text_field($value);
				break;
                        case 'tab_content':
                                $sanitized_value = wp_kses_post($value); 
                                break;
			default:
				$sanitized_value = sanitize_text_field($value);
                                break;
		}
		
		return $sanitized_value;
	}
	
}

?>