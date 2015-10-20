<?php
/*
Thinkedit config tool
*/

die ('deprecated');

class config_parser
{

  var $config; // contains the config data
  var $root=''; // contains the root of the current config instance;


  // constructor, auto loads a config file using an xml parser class
  // content is added to $this->config
  function config_parser($config_file = 'config.xml')
  {
    trigger_error('deprecated');
			require_once('xml_parser.class.php');
    $xml_parser = &new xml_parser;
    $config = &$xml_parser->parse_file($config_file);
    $this->config = $config['config'];
  }


  function &get()
  {
    return $this->config;
  }




}

?>