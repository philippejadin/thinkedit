<?php
require_once 'field.base.class.php'; 

class field_date extends field
{
		
		function renderUI($prefix = false)
		{
				$out = '';
				//$out .= sprintf('<input type="text" value="%s" name="%s", size="32">', $this->getHtmlSafe(), $prefix . $this->getName());
				//return $out;
				
				$date_array = explode('-', $this->get());
								
				if (is_array($date_array) && count($date_array) == 3)
				{
						$existing_year = $date_array[0];
						$existing_month = $date_array[1];
						$existing_day = $date_array[2];
				}
				
				$current_year = date('Y');
				$current_month = date('n');
				$current_day = date('j');
				
				if (isset($existing_day))
				{
						$selected_day = $existing_day;
				}
				else
				{
						$selected_day = $current_day;
				}
				
				if (isset($existing_month))
				{
						$selected_month = $existing_month;
				}
				else
				{
						$selected_month = $current_month;
				}
				
				
				if (isset($existing_year))
				{
						$selected_year = $existing_year;
				}
				else
				{
						$selected_year = $current_year;
				}
				
				// day
				$out.=sprintf('<select name="%s" size="1">', $prefix . $this->getName() . '[day]');
				// todo : configurable interval for years
				for ($day = 1; $day <= 31; $day ++)
				{
						if ($day == $selected_day)
						{
								$out .= sprintf('<option value="%s" selected="selected">%s &nbsp;</option>', $day, $day);
						}
						else
						{
								$out .= sprintf('<option value="%s">%s &nbsp;</option>', $day, $day);
						}
						
				}
				$out.= '</select>';
				
				// month
				$out.=sprintf('<select name="%s">', $prefix . $this->getName() . '[month]');
				// todo : configurable interval for years
				for ($month = 1; $month <= 12; $month ++)
				{
						if ($month == $selected_month)
						{
								$out .= sprintf('<option value="%s" selected="selected">%s &nbsp;</option>', $month, $month);
						}
						else
						{
								$out .= sprintf('<option value="%s">%s &nbsp;</option>', $month, $month);
						}
						
				}
				$out.= '</select>';
				
				
				// year
				$out.=sprintf('<select name="%s">', $prefix . $this->getName() . '[year]');
				// todo : configurable interval for years
				for ($year = $current_year - 10; $year < $current_year + 10; $year ++)
				{
					  if ($year == $selected_year)
						{
								$out .= sprintf('<option value="%s" selected="selected">%s &nbsp;</option>', $year, $year);
						}
						else
						{
								$out .= sprintf('<option value="%s">%s &nbsp;</option>', $year, $year);
						}
						
				}
				$out.= '</select>';
				
				return $out;
		}
		
		
		
		function set($data)
		{
				if (is_array($data))
				{
						$this->data = $data['year'] . '-' . $data['month'] . '-' . $data['day']; 
				}
				else
				{
						$this->data = $data;
				}
		}
		
}
?>
