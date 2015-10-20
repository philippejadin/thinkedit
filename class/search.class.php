<?php
/**
The search class let's you index items and then do a full text search within those items.
It needs mysql full text indexing support.
This is a work in progress
*/
class search
{
		
		/**
		When you instantiate a search object you can use a different ID if you need multiple search engines
		Currently not used
		*/
		function search($id = 'main')
		{
		}
		
		
		/**
		Index / update the search engine with $object content
		Object should provide getArray() or getIndexableContent() methods
		*/
		function index($object)
		{
				
		}
		
		/**
		Remove the $object of the index 
		*/
		function remove($object)
		{
				
		}
		
		/**
		Search the database for objects containing $string
		This uses a full text search
		
		Return an array of objects if something is found
		Else false
		*/
		function search($search_string)
		{
		}
		
		
		/*
		Will create the required table for the search engine
		Automagically called on creation of the search engine
		*/
		function initDb($db = 'main')
		{
				
		}
		
		
}


?>
