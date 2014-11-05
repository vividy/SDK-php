<?php 

error_reporting(E_ALL);

include "../config.inc.php";
include "../sdk/payname.inc.php";

$Payname = new Payname(PAYNAME_ID,PAYNAME_SECRET);

$users = $Payname->getUsers();

?>
<!DOCTYPE html>
<html>
	<meta charset='utf-8'>
	<title>Liste des utilisateurs</title>
</html>
<body>
	<p><a href="/">&lt;- liste des fonctions de l'API</a></p>
	<?php if( count($users) > 0 ) : ?>
		<table>
			<tr>
				<?php foreach ($users[0] as $key => $value) {
					echo "<th>$key</th>";
				} ?>
			</tr>
			<?php foreach ($users as $user) :  ?>
				<tr>
					<?php foreach ($user as $key => $value) {
						echo "<td>$value</td>";
					} ?>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else : ?>
		<p>Aucun utilisateur pour ce compte API.</p>
	<?php endif; ?>
</body>