# PHP-Simple-Database

A simple flat-file key/value storage class for PHP. Great for small databases or servers where SQL can't be used. Only 150 lines of code :)

### Example

```
<?php
require_once 'database.class.php';

$fruit = new Database();

//Writes Data into file
$fruit->set('types', array('apples', 'bananas', 'clementines'));
  //returns TRUE or Error

//Tells if a key is stored in the file
$fruit->search('types');
  //returns TRUE
  
$fruit->search('prices');
  //returns FALSE
  
//Returns the value of a key
$fruit->get('types');
  //returns that.object

//Deletes a value in file
$fruit->del('types');
  //returns TRUE

```
