<?php
/*
Thinkedit Install Wizard

This wizard can be called more than once (in case of db schema upgrade for instance)

There are some mandatory actions that are to be done in a certain order. 
Each time a test passes, we go to the next without showin test resutl to user. 


This one will be purely procedural code for now, as the scope and use of it is not clearly defined


What the install template uses :

- info (form previous step most of the time)
- title
- help
- content
- form
- next_url
- previous_url

Input :
- step ?

*/
// reduce error reporting : the installer will generate notices by thinkedit, because it is not yet fully installed
// this is an egg and chicken problem

error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE); 

// init (note that init should work in all situations (even if db is down))
require_once '../thinkedit.init.php';


$url = $thinkedit->newUrl();

// Ask for simple admin password

// todo : security


/***************************** General checkup *****************************/
// check general php and server environment
// if fatal problem, show info screen



/***************************** DB config *****************************/
// Is there a config file for db ?
// If not, show DB config screen
if (!isset($thinkedit->config['site']['database']['main']))
{
		// if form has been sent, update config
		if ($url->get('db_database'))
		{
				$config['site']['database']['main']['host'] = $url->get('db_host');
				$config['site']['database']['main']['database'] = $url->get('db_database');
				$config['site']['database']['main']['login'] = $url->get('db_login');
				$config['site']['database']['main']['password'] = $url->get('db_password');
				
				require_once ROOT . '/class/php_parser.class.php';
				$parser = new php_parser();
				
				if ($parser->save(ROOT . '/config/db.php', $config))
				{
				$out['info'] = 'The configuration (in /config/db.php) file has been saved';
				include_once 'install.template.php';
				exit;
				}
				else
				{
						$out['info'] = 'The configuration (in /config/db.php) file cannot be saved, please unprotect it by changing permissions on it (chmod 777 or something similar)';
						
						$out['content'] = 'If you cannot do this, you can also create the file manually and reload this installation wizard. 
						<p>A sample config file is provided in /config/db.dist.php';
						include_once 'install.template.php';
						exit;
				}
		}
		else
		{
				$out['title'] = 'Database setup';
				$out['help'] = 'Enter your database settings here';
				$out['content'] = '
				<form method="post">
				Host : <br/> <input type="text" name="db_host" value="localhost"> <br/> <br/>
				Database name :  <br/><input type="text" name="db_database"> <br/> <br/>
				Login :  <br/><input type="text" name="db_login"> <br/> <br/>
				Password :  <br/><input type="text" name="db_password"> <br/> <br/>
				
				<input type="submit">
				
				</form>
				';
				
				// include template :
				include_once 'install.template.php';
				exit;
		}
}



/***************************** DB connection *****************************/
// Can we connect to DB ?
// If not, show DB config screen + connect error info

if (!$thinkedit->db->canConnect())
{
		// if form has been sent, update config
		if ($url->get('db_database'))
		{
				$config['site']['database']['main']['host'] = $url->get('db_host');
				$config['site']['database']['main']['database'] = $url->get('db_database');
				$config['site']['database']['main']['login'] = $url->get('db_login');
				$config['site']['database']['main']['password'] = $url->get('db_password');
				
				require_once ROOT . '/class/php_parser.class.php';
				$parser = new php_parser();
				
				$parser->save(ROOT . '/config/db.php', $config);
				$out['info'] = 'The configuration (in /config/db.php) file has been saved';
				include_once 'install.template.php';
				exit;
		}
		else
		{
				
				$out['title'] = 'I cannot connect to DB server or select DB';
				$out['help'] = '(re)enter your database settings here, and ensure that the database exists and the login and password are ok';
				$out['content'] = '
				<form method="post">
				Host : <br/><input type="text" name="db_host" value="localhost"> <br/><br/>
				Database name :<br/> <input type="text" name="db_database"> <br/><br/>
				Login : <br/><input type="text" name="db_login"> <br/><br/>
				Password : <br/><input type="text" name="db_password"> <br/><br/>
				
				<input type="submit">
				
				</form>
				';
		}
		// include template :
		include_once 'install.template.php';
		exit;
}



/*
TODO todo : url path management
*/

/*

if (!isset($thinkedit->config['site']['database']['main']))
{
		// if form has been sent, update config
		if ($url->get('db_database'))
		{
				$config['site']['database']['main']['host'] = $url->get('db_host');
				$config['site']['database']['main']['database'] = $url->get('db_database');
				$config['site']['database']['main']['login'] = $url->get('db_login');
				$config['site']['database']['main']['password'] = $url->get('db_password');
				
				require_once ROOT . '/class/php_parser.class.php';
				$parser = new php_parser();
				
				$parser->save(ROOT . '/config/db.php', $config);
				$out['info'] = 'The configuration (in /config/db.php) file has been saved';
				include_once 'install.template.php';
				exit;
		}
		else
		{
				$out['title'] = 'Database setup';
				$out['help'] = 'Enter your database settings here';
				$out['content'] = '
				<form method="post">
				Host : <input type="text" name="db_host" value="localhost"> <br/>
				Database name : <input type="text" name="db_database"> <br/>
				Login : <input type="text" name="db_login"> <br/>
				Password : <input type="text" name="db_password"> <br/>
				
				<input type="submit">
				
				</form>
				';
				
				// include template :
				include_once 'install.template.php';
				exit;
		}
		
}
*/



/***************************** DB Schema *****************************/
// is the DB schema up to date ?
// if not, show info message about what can be done + button to update schema
// loop each config table

// if fix db is requested, do it beforehand :
$url = $thinkedit->newUrl();


if ($url->get('action') == 'fix_db')
{
		$out['content'] = '';
		$table_list = $thinkedit->configuration->getTableList();
		
		foreach ($table_list as $table_id)
		{
				$table = $thinkedit->newTable($table_id);
				if (!$thinkedit->db->hasTable($table->getTableName()))
				{
						$thinkedit->db->createTable($table->getTableName());
						$out['content'] .= '<li>Table ' . $table->getTableName() . ' created</li>';
				}
				else
				{
						// handle fields
						$field_list = $thinkedit->configuration->getAllFields($table->getTableName());
						foreach ($field_list as $field_id)
						{
								if (!$table->hasField($field_id))
								{
										$table->createField($field_id);
										$out['content'] .= '<li>Field ' . $field_id . ' created</li>';
								}
						}
				}
		}
		
		$out['title'] = 'The database schema has been upgraded';
		$url = $thinkedit->newUrl();
		$out['content'] .= '<a href="' . $url->render() . '">Go to next step</a>';
		// include template :
		include_once 'install.template.php';
		exit;
}

$table_list = $thinkedit->configuration->getTableList();

$out['content'] = '';

foreach ($table_list as $table_id)
{
		$table = $thinkedit->newTable($table_id);
		if ($thinkedit->db->hasTable($table->getTableName()))
		{
				// handle fields
				$field_list = $thinkedit->configuration->getAllFields($table->getTableName());
				foreach ($field_list as $field_id)
				{
						if (!$table->hasField($field_id))
						{
								$something_missing = true;
								$out['content'] .= '<li>Table ' . $table->getTableName() . ' is missing the field ' . $field_id . '</li>';
						}
						
				}
		}
		else
		{
				$something_missing = true;
				$out['content'] .= '<li>Table ' . $table_id . ' is missing </li>';
		}
}

if (isset($something_missing))
{
		$out['title'] = 'The database schema need some upgrade';
		$out['help'] = '';
		$url = $thinkedit->newUrl();
		$url->set('action', 'fix_db');
		$out['content'] .= '<a href="' . $url->render() . '">Click here to update DB (this is "riskless")</a>';
		
		// include template :
		include_once 'install.template.php';
		exit;
}







/***************************** Admin user *****************************/
// Is there a user in the DB ?
// if not, show user add screen + button to add a user
$user = $thinkedit->newRecord('user');
if ($user->count() == 0)
{
		if ($url->get('te_login') && $url->get('te_password'))
		{
				// todo security : use something like $user->register($login, $password) with check up
				$user->set('login', $url->get('te_login'));
				$user->set('password', $url->get('te_password'));
				
				$user->insert();
				
				
				$out['info'] = 'The first user has been added !';
				$out['content'] = '<a href="">Go to next step</a>';
				include_once 'install.template.php';
				exit;
		}
		else
		{
		$out['title'] = 'There is no user in the DB';
		$out['help'] = 'Please create the first admin user bellow';
		$out['content'] = '
				<form method="post">
				Login :<br/> <input type="text" name="te_login"> <br/><br/>
				Password : <br/><input type="text" name="te_password"> <br/><br/>
				
				<input type="submit">
				
				</form>
				';
		
		// include template :
		include_once 'install.template.php';
		exit;
		}
}



/***************************** Root node *****************************/
// Is there a root Node ?
// if not, ask for a title for the root node, and add it
// could be moved to structure.php
$node = $thinkedit->newNode();

if (!$node->loadRootNode())
{
		if ($url->get('te_node_title'))
		{
				$page = $thinkedit->newRecord('page');
				
				$page->set('title', $url->get('te_node_title'));
				$page->save();
				
				$node = $thinkedit->newNode();
				
				$node->saveRootNode($page);
				
				
				
				
				$out['info'] = 'The first page has been added !';
				$out['content'] = '<a href="">Go to next step</a>';
				include_once 'install.template.php';
				exit;
		}
		else
		{
		$out['title'] = 'There is no root node in the DB';
		$out['help'] = 'Please create your first page. The title can be changed later';
		$out['content'] = '
				<form method="post">
				Title :<br/> <input type="text" name="te_node_title" value="Homepage"> <br/><br/>
				<input type="submit">
				
				</form>
				';
		
		// include template :
		include_once 'install.template.php';
		exit;
		}
}

/***************************** Paths and urls *****************************/
if (!isset($thinkedit->config['site']['root_url']))
{
		// if form has been sent, update config
		if ($url->get('root_url'))
		{
				
				$path_config['site']['root_url'] = $url->get('root_url');
				require_once ROOT . '/class/php_parser.class.php';
				$parser = new php_parser();
				$parser->save(ROOT . '/config/path.php', $path_config);
				$out['info'] = 'The configuration (in /config/path.php) file has been saved';
				$out['content'] = '<a href="">Go to next step</a>';
				include_once 'install.template.php';
				exit;
		}
		else
		{
				$url = new url();
				$root_url = $url->self;
				// remove parts of the url (/install/index.php is not needed)
				$root_url = str_replace('/index.php', '', $root_url);
				$root_url = str_replace('install', '', $root_url);
				
				$out['title'] = 'Root url';
				$out['help'] = 'Verify the url to your thinkedit installation';
				$out['content'] = '
				<form method="post">
				URL :<br/> <input type="text" name="root_url" value="' . $root_url . '" size="50"> <br/><br/>
				<input type="submit">
				
				</form>
				';
		}
		// include template :
		include_once 'install.template.php';
		exit;
}



/***************************** Something else? *****************************/
// is there something else to do ?
$node = $thinkedit->newNode();

$node->rebuild();


/***************************** Congratulation ! *****************************/
// if no : show congratulation screen (other step did an exit() so this step will be shown if there is nothing to do

$out['title'] = 'Congratulation!';
$out['help'] = 'It seems everything is ready to roll!';
$out['info'] = 'Installation finished';
$out['content'] = '
You can now start using thinkedit. <a href="../">Go to your root folder and see your site</a>. You can also <a href="../edit/">go to the admin interface</a>.
Don\'t forget to return here if you change your database schema, your config files or if you upgrade. The process will be the same each time.
		<p><em>Currently, it is better to delete the install folder (/install) for security reasons.</em> Move it outside document root for instance, in case you need it again. Or password protect it.</p>		

';

// include template :
include_once 'install.template.php';
exit;





?>
