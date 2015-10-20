<?php
/**
The event class is an event manager. It can be used to :
- register an event to observe
- call event when something happens

This is a work in progress

todo : explain carefully what this does. 
This seems to be the observer pattern implemented in a very limited amount of lines :-)


Let's say you want to be notified when a record is saved

Inside the record class, everytime a record is saved, we (already/will soon) do this : 

$thinkedit->event->trigger('record_save', $this);

This means : "hey, I just saved a record, you can find it's content inside $this"


Now let's say you want to register your supper loger plugin :

$thinkedit->event->bind('record_save', 'mylogger::log()');

class mylogger
{
	function log($record)
	{
		echo $record->getTitle() . ' has been saved';
	}
}


*/
class event
{
	
	var $events;
	
	/**
	Register a $function to be called when an $event happens
	
	Example : 
	$event->bind('record_create', 'my_rss_builder');
	Would call my_rss_builder() each time a record is created
	
	*/
	function bind($event, $function, $priority = 1)
	{
		$this->events[$event][] = $function;
	}
	
	/**
	Notify the event object that something happened. 
	You can add aditional parameters those will be added to the registered function called 
	*/
	function trigger($event)
	{
		// see if there are any functions or class method registered for this $event
		if (is_array($this->events[$event]))
		{
			
			// call each function/class method for this $event using args
			
			// removes first arg (the $event name)
			for($i = 1; $i < func_num_args(); $i++) 
			{
				$args[] = func_get_arg($i);
			}
			
			foreach ($this->events[$event] as $event)
			{
				call_user_func_array ($event, $args);
			}
			
		}
		
	}
}


}
?>
