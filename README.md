Playfair cipher by John Pollard III circa 2003

Simple playfiar cipher written in php.  This was initially written as a project to help a friend learn to program.
Replaces j with i. 


FILES:

playfair_inc.php
  Has playfair_enc(key,message) and playfair_dec(key,encodedMessage) functions for including.

example.php-
  You must provide a key with a message. Works with GET and POST variables.  Otherwise it defaults to an encoded sample.
  Vars "Key" for the key and "Data" for message.

