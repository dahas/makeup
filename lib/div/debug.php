<?php

session_start();

if (!isset($_SESSION['_debug']))
	die;
$var = $_SESSION['_debug'];
$config = $_SESSION['_config'];

function createTable($config)
{
	echo '<table>';
	foreach ($config as $key => $value) {
		echo '<tr>';
		echo '<td>'.$key.'</td>';
		echo '<td>';
		if (is_array($value)) {
			echo createTable($value);
		} else {
			echo "<span style='color:blue;'>" . $value . "</span>";
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
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
		<div style="padding: 2px 0px; background: silver;"><b>Debug output</b></div>
		<div style="height: 300px; overflow: auto;">
			<pre><?php print_r($var) ?></pre>
		</div>
		<div style="padding: 8px 0px 2px; background: silver;"><b>Configuration</b></div>
		<div style="height: 354px; overflow: auto;">
			<?php echo createTable($config); ?>
		</div>
  </body>
</html>