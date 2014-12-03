<?php
require_once("../../../wp-load.php");
if ( !current_user_can( 'manage_options' ) )  {
     wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
} else {     
// output headers so that the file is downloaded rather than displayed
$date = date_create();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.date_format($date, 'Y-m-d_H:i:s').'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('*******', '*******', '*******', '*******', '*******', '*******', '*******', '*******', '*******'));

// fetch the data
mysql_connect('*******', '*******', '*******');
mysql_select_db('*******');
$rows = mysql_query('SELECT `*******`,`*******`,`*******`,`*******`,`*******`,`*******`,`*******`,`*******`,`*******` FROM *******');

// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);
}
?>