<?php
include "../../../inc/config.php";
$query = $_POST['query']." LIMIT ".$_POST['limit'];
$select = $db2->query($query);
foreach ($select as $value) {
	$rowtable[] = $db2->convertObjToArray($value);
	$table_head = $db2->convertObjToArray($value);
	$col_head_name = array_keys($table_head);

}
//dump($rowtable);

function get_data_row($key,$val) {
	echo "<td>".$key."</td>";
}
?>
                    <table class="table responsive-table" id="list_table_query">
                      <thead>
                        <tr style="background: rgb(60, 141, 188);color:#fff;cursor:auto">
                          <?php
							foreach ($col_head_name as $head) {
								echo "<th>".$head."</th>";
							}
							?>
                        </tr>
                      </thead>
                      <tbody>
                      	  <?php
							foreach ($rowtable as $row) {
								echo "<tr>";
								array_walk($row,'get_data_row');
								echo "</tr>";
							}
							?>
                      </tbody>
                  </table>