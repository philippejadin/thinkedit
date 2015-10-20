Thinkedit 2.0 Notes

- Config :

...is stored in config.xml (self explanatory)



- Filenames :

xxx.template.php : template file
xxx.php : callable file (main is the current starting point)
(xxx|common).inc.php : include file, common for all



- Templates :

We use php template files. The template file uses a variable called $out containing all the vars and array used to construct the page.

For example, $out['title'] contains the title of the current page.

other example : $out['news'][1] contains the second "news" of the page

Templates should only contain foreach, echo, translate and if structures.

like : <?php echo $out['title']?>

Translate sctructures will be used as well, like :

like : <?php echo $translate('hello_message')?> 


Names :



Locales :
(deprecated)
Locales are of different type :

- interface locale : translation of the interface error messages, buttons and info
-> $interface_locale

- $db_locale : working locale inside the db.
-> $db_locale


- $prefered_locale : user prefered locale.  Used to know which locale is prefered by the user as a working locale