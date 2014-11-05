<?php 

error_reporting(E_ALL);

include "../config.inc.php";
include "../sdk/payname.inc.php";

$Payname = new Payname(PAYNAME_ID,PAYNAME_SECRET);

$data = array(
	"email" => "example@example.fr",
	"first_name" => "example"
);

$user = $Payname->createUser($data);

?>
<!DOCTYPE html>
<html>
	<meta charset='utf-8'>
	<title>Création d'un utilisateur</title>
</html>
<body>
	<p><a href="/">&lt;- liste des fonctions de l'API</a></p>
	<?php if( $user ) : ?>
		<table>
			<tr><th>Key</th><th>Value</th></tr>
			<?php foreach ($user as $key => $value) {
				echo "<tr><td>$key</td><td>$value</td></tr>";
			} ?>
		</table>
	<?php else : ?>
		<p>Impossible de créer l'utilisateur.</p>
	<?php endif; ?>
</body>