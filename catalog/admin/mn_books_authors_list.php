<?php

set_include_path(get_include_path() . PATH_SEPARATOR . "/public/vhost/g/gutenberg/dev/private/lib/php");
include_once ("pgcat.phh");
include_once ("sqlform.phh");
include_once ("mn_relation.phh");

$db = $config->db ();
$db->logger = new logger ();

pageheader ($caption = MNCaption ("Author", "Book"));

getint ("fk_books");

class ListAuthorsTable extends ListTable {
  function __construct () {
    global $fk_books;
    $this->AddColumn ("<a href=\"mn_books_authors?mode=add&" . 
		      "fk_books=$fk_books&fk_authors=#pk#\">Link</a>", "", null, "1%");
    $this->AddSimpleColumn ("author", "Name");
    $this->AddSimpleColumn ("born_floor",   "Born", "narrow right");
    $this->AddSimpleColumn ("died_floor",   "Died", "narrow right");
  }
}

echo ("
<p>Please enter the first few characters of the authors name (at least one).
Use * as wildcard.</p>

<form action=\"{$_SERVER['PHP_SELF']}\">
<input type=\"text\" name=\"filter\" value=\"$filter\"/>
");
form_relay  ("mode");
form_relay  ("fk_books");
form_submit ("Search");
echo ("</form>\n");

if ($filter != "") {
  $filter = str_replace ('*', '%', $filter);
  $sql_filter = $db->f ("$filter%", SQLCHAR);
  $db->exec ("select * from authors where author ilike $sql_filter order by author;");
  $table = new ListAuthorsTable ();
  $table->PrintTable ($db, $caption);
}

p ("<a href=\"author?mode=add\">Add Author</a>");

pagefooter ();

?>
