<?php

/*
an ultra thin cache wrapper
who needs more ?
*/

class cache
{
		function cache()
		{
				global $thinkedit;
				$this->cache = $thinkedit->getCache();
		}
		
		function get($id)
		{
				return $this->cache->get($id);
		}
		
		function set($id, $data)
		{
				return $this->cache->save($data, $id);
		}
		
		function delete($id)
		{
				return $this->cache->remove($id);
		}
		function deleteAll()
		{
				return $this->cache->clean();
		}
		
}

?>
