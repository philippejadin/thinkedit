<?php
$data['content']['page']['title']['fr']='Page';
$data['content']['page']['title']['en']='Page';
$data['content']['page']['help']['fr']='Une page du sites';
$data['content']['page']['help']['en']='A page of the site';
$data['content']['page']['title_field']='title';
$data['content']['page']['allowed_items']['record']['page']='true';
$data['content']['page']['use']['navigation']='true';


$data['content']['page']['field']['id']['type']='id';
$data['content']['page']['field']['title']['title']['fr']='Titre';
$data['content']['page']['field']['title']['title']['en']='Title';
$data['content']['page']['field']['title']['help']['fr']='Un titre court est plus percutant';
$data['content']['page']['field']['title']['help']['en']='A short title is often better';
$data['content']['page']['field']['title']['type']='string';
$data['content']['page']['field']['title']['is_title']='true';


$data['content']['page']['field']['sub_title']['title']['fr']='Sous titre';
$data['content']['page']['field']['sub_title']['help']['fr']='Utilisé dans la page si vous en proposez un';
$data['content']['page']['field']['sub_title']['title']['en']='Sub title';
$data['content']['page']['field']['sub_title']['help']['en']='May be used by the page template if you provide one';
$data['content']['page']['field']['sub_title']['type']='string';

/*
$data['content']['page']['field']['intro']['title']['fr']='Introduction';
$data['content']['page']['field']['intro']['title']['en']='Introduction';
$data['content']['page']['field']['intro']['type']='richtext';
*/

$data['content']['page']['field']['body']['title']['fr']='Corps du texte';
$data['content']['page']['field']['body']['title']['en']='Body';
$data['content']['page']['field']['body']['type']='richtext';




//$data['content']['page']['field']['body']['type']='wiki';

// we could use fck instead :  
//$data['content']['page']['field']['body']['engine']='fck';

// a css could be provided to tinymce :
//$data['content']['page']['field']['body']['css']='content.css';

// bbcode could be used as an output filter
//$data['content']['page']['field']['body']['outputfilter']='bbcode';

// we could trim before saving
//$data['content']['page']['field']['body']['inputfilter']='trim';

//$data['content']['page']['field']['body']['validation']['is_required']=1;
$data['content']['page']['field']['cover']['title']['fr']='Image de présentation';
$data['content']['page']['field']['cover']['help']['fr']='Facultative';
$data['content']['page']['field']['cover']['title']['en']='"Cover" image';
$data['content']['page']['field']['cover']['help']['en']='Not mandatory';
$data['content']['page']['field']['cover']['type']='file';


/*
$data['content']['translation']['title']['fr']='Traduction';
$data['content']['translation']['help']['fr']='Les traductions peuvent être utilisées dans l\'interface et dans la partie publique du site. C\'est très utile dans le cas d\'un site multilingue';
$data['content']['translation']['use']['navigation']='false';
$data['content']['translation']['field']['translation_id']['type']='stringid';
$data['content']['translation']['field']['translation_id']['is_title']='true';
$data['content']['translation']['field']['translation']['type']='text';
$data['content']['translation']['field']['translation']['is_title']='true';
$data['content']['translation']['field']['locale']['type']='locale';
$data['content']['translation']['field']['locale']['is_title']='true';
*/

/*
$data['content']['author']['title']['fr']='Auteur';
$data['content']['author']['field']['id']['type']='id';
$data['content']['author']['field']['id']['primary']='true';
$data['content']['author']['field']['firstname']['type']='string';
$data['content']['author']['field']['firstname']['is_title']='true';
$data['content']['author']['field']['lastname']['type']='string';
$data['content']['author']['field']['lastname']['is_title']='true';
$data['content']['author']['allowed_items']='none';
$data['content']['author']['use']['navigation']='false';
*/


$data['content']['relation']['title_field']='title';
$data['content']['relation']['field']['id']['type']='id';
$data['content']['relation']['field']['id']['primary']='false';
$data['content']['relation']['field']['source_class']['type']='string';
$data['content']['relation']['field']['source_class']['primary']='true';
$data['content']['relation']['field']['source_class']['use']['list']='true';
$data['content']['relation']['field']['source_type']['type']='string';
$data['content']['relation']['field']['source_type']['primary']='true';
$data['content']['relation']['field']['source_type']['use']['list']='true';
$data['content']['relation']['field']['source_id']['type']='text';
$data['content']['relation']['field']['source_id']['primary']='true';
$data['content']['relation']['field']['source_id']['use']['list']='true';
$data['content']['relation']['field']['target_class']['type']='string';
$data['content']['relation']['field']['target_class']['primary']='true';
$data['content']['relation']['field']['target_class']['use']['list']='true';
$data['content']['relation']['field']['target_type']['type']='string';
$data['content']['relation']['field']['target_type']['primary']='true';
$data['content']['relation']['field']['target_type']['use']['list']='true';
$data['content']['relation']['field']['target_id']['type']='text';
$data['content']['relation']['field']['target_id']['primary']='true';
$data['content']['relation']['field']['target_id']['use']['list']='true';
$data['content']['relation']['field']['sort_order']['type']='order';
$data['content']['relation']['field']['sort_order']['use']['list']='true';




$data['content']['user']['title']['fr']='Utilisateurs';
$data['content']['user']['help']['fr']='Liste des personnes pouvant utiliser le site et modifier son contenu';
$data['content']['user']['title']['en']='Users';
$data['content']['user']['help']['en']='List of the users that can log in to the system';

$data['content']['user']['use']['main']='true';
$data['content']['user']['use']['navigation']='false';
$data['content']['user']['field']['id']['type']='id';
$data['content']['user']['field']['id']['primary']='true';
$data['content']['user']['field']['login']['is_title']='true';
$data['content']['user']['field']['login']['type']='login';
$data['content']['user']['field']['password']['type']='password';
$data['content']['user']['field']['password']['use']['list']='false';
$data['content']['user']['field']['interface_locale']['type']='string';
$data['content']['user']['field']['interface_locale']['title']['fr']='Langue de l\'interface';
$data['content']['user']['field']['interface_locale']['title']['en']='Interface locale';


$data['content']['node']['field']['id']['type']='id';
$data['content']['node']['field']['id']['primary']='true';
$data['content']['node']['field']['id']['is_title']='true';
$data['content']['node']['field']['parent_id']['type']='int';
$data['content']['node']['field']['parent_id']['use']['edit']='false';
$data['content']['node']['field']['object_class']['type']='string';
$data['content']['node']['field']['object_class']['use']['list']='false';
$data['content']['node']['field']['object_class']['use']['edit']='false';
$data['content']['node']['field']['object_type']['type']='string';
$data['content']['node']['field']['object_type']['use']['list']='false';
$data['content']['node']['field']['object_type']['use']['edit']='false';
$data['content']['node']['field']['object_id']['type']='text';
$data['content']['node']['field']['object_id']['use']['list']='false';
$data['content']['node']['field']['object_id']['use']['edit']='false';
$data['content']['node']['field']['sort_order']['type']='order';
$data['content']['node']['field']['sort_order']['use']['list']='false';
$data['content']['node']['field']['sort_order']['use']['edit']='false';
$data['content']['node']['field']['level']['type']='int';
$data['content']['node']['field']['level']['use']['list']='false';
$data['content']['node']['field']['level']['use']['edit']='false';
$data['content']['node']['field']['path']['type']='string';
$data['content']['node']['field']['path']['use']['list']='false';
$data['content']['node']['field']['path']['use']['edit']='false';
$data['content']['node']['field']['cache']['type']='text';
$data['content']['node']['field']['cache']['use']['list']='false';
$data['content']['node']['field']['cache']['use']['edit']='false';
$data['content']['node']['field']['template']['title']['fr']='Modèle de page';
$data['content']['node']['field']['template']['title']['en']='Page template';
$data['content']['node']['field']['template']['type']='template';
$data['content']['node']['field']['publish']['title']['fr']='Statut de publication';
$data['content']['node']['field']['publish']['title']['en']='Publish status';
$data['content']['node']['field']['publish']['type']='publish';
$data['content']['node']['field']['created_date']['title']['fr']='Date de création';
$data['content']['node']['field']['created_date']['title']['en']='Creation date';
$data['content']['node']['field']['created_date']['type']='created';

$data['content']['node']['field']['left_id']['type']='int';
$data['content']['node']['field']['left_id']['use']['list']='false';
$data['content']['node']['field']['left_id']['use']['edit']='false';
$data['content']['node']['field']['right_id']['type']='int';
$data['content']['node']['field']['right_id']['use']['list']='false';
$data['content']['node']['field']['right_id']['use']['edit']='false';





/*
$data['content']['role']['title']['fr']='Roles';
$data['content']['role']['help']['fr']='Roles des différentes personnes qui utilisent l\'interface';
$data['content']['role']['use']['main']='true';
$data['content']['role']['field']['id']['type']='id';
$data['content']['role']['field']['title']['type'] = 'string';
$data['content']['role']['field']['title']['is_title']='true';

$data['content']['permission']['title']['fr'] = 'Permissions';
$data['content']['permission']['help']['fr'] = 'Permissions assignables à un rôle';
$data['content']['permission']['use']['main']='true';
$data['content']['permission']['field']['id']['type'] = 'id';
$data['content']['permission']['field']['title']['type'] = 'string';
$data['content']['permission']['field']['title']['is_title'] = 'true';
$data['content']['permission']['field']['action']['type'] = 'string';
$data['content']['permission']['field']['object_class']['type']='string';
$data['content']['permission']['field']['object_type']['type']='string';
$data['content']['permission']['field']['object_id']['type']='text';
*/


?>
