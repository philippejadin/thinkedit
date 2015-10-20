<?php
/*
See licence.txt for licence

File manager manages files stored locally or not, dependig on the class used
*/

include_once('common.inc.php');

//check_user
check_user();

$filesystem = $thinkedit->newFilesystem();

// path is the current folder we are

if ($url->get('path'))
{
		$path = $url->get('path');
		$filesystem->setPath($path);
}


$out['path'] =  $filesystem->getPath();

// handle adding of a new file from http upload
if ($url->get('action') == 'upload_file')
{
		if ($filesystem->addFileFromUpload('uploaded_file'))
		{
				$out['info'] = translate('file_added_successfully');
		}
		else
		{
				$out['error'] = translate('file_added_failed');
		}
}
		
		/*
		// Check if we have a file uploaded
		if (isset($_FILES['uploaded_file']['size']) && $_FILES['uploaded_file']['size'] > 0)
		{
				if (is_uploaded_file($_FILES['uploaded_file']['tmp_name'])) // todo : is it enough for a check ?
				{
						debug($_FILES['uploaded_file'], 'UPLOADED FILES');
						//print_r($_FILES['uploaded_file']);
						//safe filename
						
						// todo move this name conversion to filesystem class :
						// done
						$safe_filename = $filesystem->safeFileName($_FILES['uploaded_file']['name']);
						
						$content = file_get_contents($_FILES['uploaded_file']['tmp_name']);
						
						if ($filesystem->addFile($safe_filename, $content))
						{
								$out['info'] = translate('file_added_successfully');
						}
						else
						{
								$out['error'] = translate('file_added_failed');
						}
						
				}
				else
				{
						die('not an uploaded file aborting');
				}
				
		}
		else
		{
				$out['error'] = translate('empty_file_provided');
		}
	
}
*/


// handle adding of a new folder 
if ($url->get('action') == 'add_folder')
{
		if ($url->get('folder_name'))
		{
				$folder_name = $url->get('folder_name');
				if ($filesystem->addFolder($folder_name))
				{
						$out['info'] = translate('folder_added_successfully');
				}
				else
				{
						$out['error'] = translate('folder_added_failed');
				}
		}
		else
		{
				$out['error'] = translate('empty_foldername_provided');
		}
}





// handle deletion of a file on the ftp server

if ($url->get('action') == 'delete')
{
		//$server->rm($_REQUEST['file_to_delete']);
		if ($url->get('file_to_delete'))
		{
				$tmp_filesystem = $thinkedit->newFilesystem();
				$tmp_filesystem->setPath($url->get('file_to_delete'));
				
				if ($tmp_filesystem->delete())
				{
						$out['info'] = translate('file_deleted');
				}
				else
				{
						$out['error'] = translate('file_delete_failled');
				}
		}
		
		
}


// build a list of folders (excluding cache and thumbnails folders / files)
// we use a new instance of filesystem to have all folders form root allways
$filesystem2 = $thinkedit->newFilesystem();
$folders = $filesystem2->getFolderListRecursive();

debug($folders, 'folders');

if ($folders)
{
		$url = new url();
		foreach ($folders as $folder)
		{
				$folder_out = '';
				
				$url->set('path', $folder->getPath());
				
				$folder_out['path'] = $folder->getPath();
				$folder_out['url'] = $url->render();
				if ($folder->getPath() == $filesystem->getPath())
				{
						$folder_out['current'] = true;
				}
				
				
				$out['folders'][] = $folder_out;
		}
		
}


/*
// handle icons for each file

$dir = dirname($_SERVER['PATH_TRANSLATED']) . "/icons/extensions";
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh)))
{
		$icon_name = get_file_filename($filename);
		if ($icon_name <> '') 	 $icons[] = $icon_name;
		
}
*/

/*
print_a($icons);

die();
*/

// todo




// build a list of files in the current folder (excluding cache and thumbnails folders / files)

//$files = $db->get_results("select * from $table where path='$path' group by 'id' order by filename");
//if ($debug) $db->debug();

//if ($debug) print_a ($files);

$childs = $filesystem->getFiles();

if ($childs)
{
		foreach ($childs as $child)
		{
				$file['filename'] = $child->getFilename();
				$file['icon'] = $child->getIcon();
				
				if ($child->isFolder())
				{
						$url = new url();
						$url->set('path', $child->getPath());
						$file['url'] = $url->render();
				}
				
				$url = new url();
				$url->keep('path');
				$url->set('file_to_delete', $child->getPath());
				$url->set('action', 'delete');
				$file['delete_url'] = $url->render();
				
				$out['files'][] = $file;
		}
}




// handle add folder


// handle remove folder

// handle sync with folder


// define action buttons urls
$url = new url();
$url->keep('path');
$url->set('action', 'add_folder');
$out['add_folder_url'] = $url->render();

$url = new url();
$url->keep('path');
$url->set('action', 'upload_file');
$out['upload_file_url'] = $url->render();


// add breadcrumb
$url = new url();
$out['breadcrumb'][1]['title'] = translate('filemanager_title');
$out['breadcrumb'][1]['url'] = $url->render();



debug($out, 'OUT');

//print_a($out);

// include template :
include('header.template.php');
include('file_manager.template.php');
//include('list.template.php');
include('footer.template.php');
?>
