<?php
/**
The trash will store deleted objects and will let users restore those items where they were originally
It will also let you list it's content

This is a work in progress
*/
class trash
{
		
		/**
		Returns the content of the trash
		*/
		function getContent()
		{
		}
		
		
		/**
		The passed object is put into the trash
		*/
		function delete($object)
		{
		}
		
		
		/**
		The passed object is restored to original location / table / wathever, if possible 
		Else it is restored in the nearest avaialbel location (for instance, the nearest parent)
		*/
		function restore($object)
		{
		}
		
		/**
		Empty the trash content for ever and eventually removes all the relations the nodes / records could still have
		This cannot be undone of course
		*/
		function emptyForEver()
		{
		}
		
		/**
		Remove for ever the $object from the trash content for ever and eventually removes all the relations the $object could still have
		This cannot be undone of course
		*/
		function deleteObjectForEver($object)
		{
		}
		
}


?>
