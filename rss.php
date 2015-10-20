<?php
/*
Preliminary RSS output support for Thinkedit
May be converted to a plugin later

Will list the 20 last created nodes


From http://www.xul.fr/xml-rss.html#cr%E9ation-flux 

<rss version="2.0">
<channel>
    <title>XUL</title>     
    <link>http://www.xul.fr</link>
    <description></description>
    <item>
        <title></title>
        <link></link>
        <description></description>
    </item>    
</channel>
</rss>
*/
require_once 'thinkedit.init.php';

error_reporting(E_ALL);
ini_set('display_errors', true);

header("Content-type: text/xml");
echo '<' . '?xml version="1.0"?>'. "\n";
echo '<rss version="2.0">'. "\n";
echo '<channel>'. "\n"; 

$root = $thinkedit->newNode();
$root->loadRootNode();

echo '<title>'. strip_tags ($root->getTitle()) .'</title>'. "\n";
echo '<link>TODO</link>'. "\n";

$root_content = $root->getContent();
echo '<description><![CDATA[' . "\n" . strip_tags ($root_content->get('body')) . "\n" . ']]></description>'; 
echo ''; 


$db = $thinkedit->getDb();

$results = $db->select('select * from node where publish = 1 order by created_date desc, id desc limit 1, 20');

if ($results)
{
		foreach ($results as $result)
		{
				$node = $thinkedit->newNode();
				$node->load($result['id']);
				
				echo '<item>'. "\n";
				echo '<title>' . ' <![CDATA[' . "\n" . $node->getTitle() . "\n" . ']]>' . '</title>'. "\n";
				echo '<link>' . ' <![CDATA[' . "\n" . 'http://' . $_SERVER["SERVER_NAME"] . te_link($node, true) . "\n" . ']]>' . '</link>'. "\n";
				
				$content = $node->getContent();
				$content->load();
				
				if ($content->get('intro'))
				{
						$intro = $content->get('intro');
				}
				elseif ($content->get('body'))
				{
						$intro = te_short($content->get('body'), 200);
				}
				else
				{
						$intro = 'no description available';
				}
				
				
				echo '<description>' . ' <![CDATA[' . "\n" . $intro . "\n" . ']]>' . '</description>' . "\n";
				echo '</item>'. "\n";
				
		}
}
echo '</channel>'. "\n"; 
echo '</rss>'. "\n"; 

?>
