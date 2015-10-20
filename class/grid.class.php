<?php
/*

Handle rendering of a data grid / list, main element of the admin interface


Deals only view the view side of things (html + persistence of list status)
Some view logic is allowed


*/
class grid
{
		
		var $data;
		var $column;
		var $checkboxes = false;
		
		
		function datagrid()
		{
				//$this->checkboxes = false;
		}
		
		
		/*
		Needs to be given an array indexed by field id
		
		$data['id'] = '1'
		$data['title'] = 'MyTitle'
		
		If a column called id and title is added, it will be shown
		
		*/
		function add($data)
		{
				$this->data[] = $data; 
		}
		
		
		
		/*
		adds more than one row
		*/
		function addMany($data)
		{
				foreach($data as $row)
				{
						$this->add($row);
				}
				
		}
		
		
		
		
		
		/*
		Adds a column
		*/
		function addColumn($id, $args)
		{
				$this->column[$id] = $args;
		}
		
		
		function enableCheckBox()
		{
				$this->checkboxes = true;
		}
		
		
		
		function render($type='list')
		{
				if ($type=='list')
				{
						return $this->renderAsList();
				}
				if ($type=='icon')
				{
						return $this->renderAsIcons();
				}
				
		}
		
		
		
		
		function renderAsList()
		{
				$out = '';
				$out.='<table border="1" class="datagrid">';
				
				if (is_array($this->column))
				{ 
						foreach ($this->column as $id=>$column)
						{
								$out.='<th class="datagrid">' . $column['title'] .'</th>';
						}
				}
				else
				{
						trigger_error('datagrid::render() no column defined');
				}
				
				if (is_array($this->data))
				{
						foreach ($this->data as $data)
						{
								$out.='<tr class="datagrid">';
								foreach ($this->column as $id=>$column)
								{
										if (isset($data[$id]))
										{
												$out.='<td class="datagrid">' .$data[$id] .'</td>';
										}
										else
										{
												$out.='<td class="datagrid"></td>';
										}
								}
								
								
								$out.='</tr>';
						}
				}
				else
				{
						$out.='<tr class="datagrid">';
						$out.='<td class="datagrid">' . translate('empty_datagrid').'</td>';
						$out.='</tr>';
				}
				
				
				
				$out .= '</table>';
				
				
				return $out;
		}
		
		
		
		
		
		function renderAsIcons()
		{
				$out = '';
				
				
				if (isset($this->pager))
				{
						$out.=$this->pager->render();			
				}
				
				// $out.='<table border="1">';
				/*
				if (is_array($this->column))
				{
						foreach ($this->column as $id=>$column)
						{
								$out.='<th>' . $column['title'] .'</th>';
						}
				}
				else
				{
						trigger_error('datagrid::render() no column defined');
				}
				
				if (is_array($this->local_action))
				{
						$out.='<th>' . translate('datagrid_action_header') .'</th>';
				}
				*/
				
				
				if (is_array($this->data))
				{
						$i = 0;
						foreach ($this->data as $data)
						{
								$i++;
								if ($i >= count ($this->data))
								{
										$out.='<table class="icon_table" style="float: none">';
								}
								else
								{
										$out.='<table class="icon_table" style="float: left">';
								}
								$out.='<tr>';
								$out.='<td>';
								$out.='<img src="decoration/types/folder.png">';
								$out.='</td>';
								$out.='</tr>';
								$out.='<tr>';
								$out.='<td>';
								$out.= substr($data['title'], 0, 32);
								
								$out.='<br/>';
								
								
								// add local action buttons
								if (is_array($this->local_action))
								{
										//$out .= '<td>';
										foreach ($this->local_action as $action)
										{
												require_once ROOT . '/class/url.class.php';
												$url = new url();
												
												// find the primary columns to pass to the url
												foreach ($this->column as $id=>$column)
												{
														if ($column['primary'])
														{
																$url->setParam($id, $data[$id]);
														}
												}
												
												$url->setFilename($action['url']);
												
												$out .= '<a href="' . $url->render() .'">' . $action['title'] . '</a> ';
												
										}
								}
								
								
								
								$out.='</td>';
								$out.='</tr>';
								$out.='</table>';
								
								
								
								foreach ($this->column as $id=>$column)
								{
										// $out.='<td>' . substr($data[$id], 0, 32) .'</td>';
								}
								
								
								
								
						}
				}
				else
				{
						
						translate('empty_datagrid');
						
				}
				
				
				
				
				
				// add local action buttons
				if (is_array($this->global_action))
				{
						
						
						foreach ($this->global_action as $action)
						{
								require_once ROOT . '/class/url.class.php';
								$url = new url();
								
								$url->setFilename($action['url']);
								require_once ROOT . '/class/button.class.php';
								$button = new button($action['title'], $url->render()); 
								
								$out .= $button->render();
								
						}
						
						
						
				}
				
				
				return $out;
		}
		
		
		
		
}

?>
