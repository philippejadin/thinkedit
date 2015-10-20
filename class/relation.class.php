<?php
class relation
{
		function relation($table = "relation") // todo, configure someway
		{
				global $thinkedit;
				$this->table = $table;
				//parent::record($table);
				$this->record = $thinkedit->newRecord($this->table);
				
		}
		
		function relate($source, $target)
		{
				$source_uid = $source->getUid();
				$target_uid = $target->getUid();
				
				$this->record->set('source_class', $source_uid['class']);
				$this->record->set('source_type', $source_uid['type']);
				$this->record->set('source_id', $source_uid['id']);
				
				$this->record->set('target_class', $target_uid['class']);
				$this->record->set('target_type', $target_uid['type']);
				$this->record->set('target_id', $target_uid['id']);
				
				if ($this->record->save())
				{
						return true;
				}
				else
				{
						return false;
				}
				
				
		}
		
		function unRelate($source, $target)
		{
				$source_uid = $source->getUid();
				$target_uid = $target->getUid();
				
				$this->record->set('source_class', $source_uid['class']);
				$this->record->set('source_type', $source_uid['type']);
				$this->record->set('source_id', $source_uid['id']);
				
				$this->record->set('target_class', $target_uid['class']);
				$this->record->set('target_type', $target_uid['type']);
				$this->record->set('target_id', $target_uid['id']);
				
				if ($this->record->delete(true))
				{
						return true;
				}
				else
				{
						return false;
				}
		}
		
		/*
		Returns relations of a given $object
		$object : object we'd like to find relations of
		$options is an array
		  - type : only relations of a given type
			- class : only relations of a given class
			- bidirectional (boolean) : set to true if you want bidirectional relations
			
		*/
		function getRelations($object, $options = false)
		{
				global $thinkedit;
				$uid = $object->getUid();
				
				// 1. find any relation in the source columns
				// construct where clause
				$where = array();
				$where['source_class'] = $uid['class'];
				$where['source_type'] = $uid['type'];
				$where['source_id'] = $uid['id'];
				if (isset ($options['type']))
				{
						$where['target_type'] = $options['type'];
				}
				if (isset ($options['class']))
				{
						$where['target_class'] = $options['class'];
				}
				$results_1 = $this->record->find($where);
				
				
				// if we are asked to provide reverse relations, we do it 
				if (isset ($options['bidirectional']))
				{
						// find any relation in the target columns
						$where = array();
						$where['target_class'] = $uid['class'];
						$where['target_type'] = $uid['type'];
						$where['target_id'] = $uid['id'];
						if (isset ($options['type']))
						{
								$where['source_type'] = $options['type'];
						}
						
						if (isset ($options['class']))
						{
								$where['source_class'] = $options['class'];
						}
						
						$results_2 = $this->record->find($where);
				}
				
				if (is_array($results_1))
				{
						foreach ($results_1 as $result)
						{
								$uid['class'] = $result->get('target_class');
								$uid['type'] = $result->get('target_type');
								$uid['id'] = $result->get('target_id');
								$item = $thinkedit->newObject($uid);
								$item->load();
								$items[] = $item;
						}
				}
				
				if (isset ($options['bidirectional']))
				{
						if (is_array($results_2))
						{
								foreach ($results_2 as $result)
								{
										$uid['class'] = $result->get('source_class');
										$uid['type'] = $result->get('source_type');
										$uid['id'] = $result->get('source_id');
										$item = $thinkedit->newObject($uid);
										$item->load();
										$items[] = $item;
								}
						}
				}
				
				if (isset($items))
				{
						return $items;
				}
				else
				{
						return false;
				}
				
		}
		
		
		
		
		/*
		big todo here, and it will be hard !
		*/
		function moveUp()
		{
				// first find items before this one
				
				
				
				// if we have 2 or more
				
				// if we have one, move top
				
				// if we have none
				// we are at top, do nothing
		}
		
		function moveDown()
		{
		}
		
		function moveToBottom()
		{
		}
		
		function moveToTop()
		{
		}
		
		
}



?>
