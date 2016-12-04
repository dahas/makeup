<?php

session_start();

$var = null;
if (isset($_SESSION['_debug'])) {
	$var = $_SESSION['_debug'];
}

	$config = $_SESSION['_config'];
	
function createTable($config)
{
	$table = '<table>';
	foreach ($config as $key => $value) {
		$table.= '<tr>';
		$table.= '<td>' . $key . '</td>';
		$table.= '<td>';
		if (is_array($value)) {
			$table.= createTable($value);
		} else {
			$table.= "<span style='color:blue;'>" . $value . "</span>";
		}
		$table.= '</td>';
		$table.= '</tr>';
	}
	$table.= '</table>';
	return $table;
}

?>
<html>
  <head>
    <title>Debug output</title>
		<style>
			*, html, body {
				padding: 0;	margin: 0;
				font-family: Tahoma, Arial, Helvetica;
				font-size: 12px;
			}
			table {
				display: table; width: 100%;
				border: 0; background: white;
			}
			table tr td {
				display: table-cell; white-space: nowrap;
				vertical-align: top; padding: 2px;
				margin:0px; background: #ddd;
			}
		</style>
  </head>
  <body style="background: #eee; overflow:hidden;">
		<?php if($var): ?>
		<div style="padding: 4px 0px; background: silver;"><b>Debug output</b></div>
		<div style="height: 300px; overflow: auto;">
			<pre><?php print_r($var) ?></pre>
		</div>
		<?php endif; ?>
		<div style="padding: 4px 0px 2px; background: silver;"><b>Configuration</b></div>
		<div style="height: 354px; overflow: auto;">
<?php echo createTable($config); ?>
		</div>
  </body>
</html>
<?php
unset($_SESSION['_debug']);
?>