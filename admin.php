<?php
require "stranky.php";

session_start();

$chyba = null;

// zpracovani prihlasovaciho formulare
if (array_key_exists("prihlasit", $_POST))
{
	$jmeno = $_POST["jmeno"];
	$heslo = $_POST["heslo"];

	// TODO Doresit bezpecnost hesla!
	if ($jmeno == "admin" && $heslo == "1234")
	{
		// uzivatel zadal platne prihlasovaci udaje
		$_SESSION["prihlasenyUzivatel"] = $jmeno;
	}
	else
	{
		// nespravne prihlasovaci udaje
		$chyba = "Nesprávné přihlašovací údaje";
	}
}

// zpracovani odhlaseni
if (array_key_exists("odhlasit", $_POST))
{
	unset($_SESSION["prihlasenyUzivatel"]);
	header("Location: ?");
}

// zpracovani akci v administraci je pouze pro prihlasene uzivatele
if (array_key_exists("prihlasenyUzivatel", $_SESSION))
{
	// promenna predstavujici stranku, kterou zrovna editujeme
	$instanceAktualniStranky = null;

	// zpracovani vyberu aktualni stranky
	if (array_key_exists("stranka", $_GET))
	{
		$idStranky = $_GET["stranka"];
		$instanceAktualniStranky = $seznamStranek[$idStranky];
	}

	// zpracovani tlacitka pridat
	if (array_key_exists("pridat", $_GET))
	{
		$instanceAktualniStranky = new Stranka("","","");
	}

	// zpracovani tlacitka smazat
	if (array_key_exists("smazat", $_GET))
	{
		$instanceAktualniStranky->smazat();
		header("Location: ?"); // presmerovani domu
	}

	// zpracovani formulare pro ulozeni
	if (array_key_exists("ulozit", $_POST))
	{
		// poznamename si puvodmi id, nez si ho prepiseme
		$puvodniId = $instanceAktualniStranky->id;

		$instanceAktualniStranky->id = $_POST["id"];
		$instanceAktualniStranky->titulek = $_POST["titulek"];
		$instanceAktualniStranky->menu = $_POST["menu"];
		// zavolame funkci pro ulozeni zmenenych hodnot do databaze
		$instanceAktualniStranky->ulozit($puvodniId);

		// ukladani obsahu stranky
		$obsah = $_POST["obsah"];
		$instanceAktualniStranky->setObsah($obsah);

		// presmerovani na novou url dle noveho id stranky
		header("Location: ?stranka=".urlencode($instanceAktualniStranky->id));
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Administrace</title>

	<link rel="stylesheet" href="css/admin.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</head>
<body>
	<div class="admin-body">
		<?php
		if (array_key_exists("prihlasenyUzivatel", $_SESSION) == false)
		{
			// sekce pro neprihlasene uzivatele
		?>
		<main class="form-signin">
			<form method="post">
				<h1 class="h3 mb-3 fw-normal">Přihlašte se, prosím</h1>
				
				<?php if ($chyba != "") {
				?>
					<div class="alert alert-danger" role="alert">
                    	<?php echo $chyba; ?>
                	</div>
				<?php } ?>

				<div class="form-floating">
					<input name="jmeno" type="text" class="form-control" id="floatingInput" placeholder="Login">
					<label for="floatingInput">Přihlašovací jméno</label>
				</div>

				<div class="form-floating">
					<input name="heslo" type="password" class="form-control" id="floatingPassword" placeholder="Heslo">
					<label for="floatingPassword">Heslo</label>
				</div>

				<button name="prihlasit" class="w-100 btn btn-lg btn-primary" type="submit">Přihlásit</button>
			</form>
		</main>
		<?php
		}
		else
		{
			// sekce pro prihlasene uzivatele
			echo "<main class='admin'>";

			?>
            <div class="container">
                <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
					<div>Přihlášený uživatel: <?php echo $_SESSION["prihlasenyUzivatel"]; ?></div>

                	<div class="col-md-3 text-end">
						<form method='post'>
							<button name='odhlasit' class="btn btn-outline-primary me-2">Odhlásit</button>
						</form>
                	</div>
                </header>
            </div>
            <?php
			// vypiseme seznam stranek k editaci
			echo "<ul id='stranky' class='list-group'>";
			foreach ($seznamStranek as $idStranky => $instanceStranky)
			{
				$active = '';
				$buttonClass = 'btn-primary';
				if ($instanceStranky == $instanceAktualniStranky)
				{
					$active = 'active';
					$buttonClass = 'btn-secondary';
				}
				echo "<li class='list-group-item $active'>
				<a class= 'btn $buttonClass' href='?stranka=$instanceStranky->id&smazat'><i class='fa-solid fa-trash-can'></i></a>

				<a class= 'btn $buttonClass' href='?stranka=$instanceStranky->id'><i class='fa-solid fa-pen-to-square'></i></a>
				
				<a class= 'btn $buttonClass' href='$instanceStranky->id' target='_blank'><i class='fa-solid fa-eye'></i></a>

				<span>$instanceStranky->id</span>
				</li>";
			}
			echo "</ul>";

			// tlacitko pro pridani stranky
			?>
			<div class="container">
                <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
                	<div class="col-md-3 text-start">
						<form>
							<button name='pridat' class="btn btn-outline-primary me-2">Přidat novou stránku</button>
						</form>
                	</div>
                </header>
            </div>

			<?php
			// editacni formular - zobrazi se, pokud vybereme nejakou stranku k editaci
			if ($instanceAktualniStranky != null)
			{
				echo "<div class='alert alert-secondary' role='alert'><h1>";
				if ($instanceAktualniStranky->id == "")
				{
					echo "Nová stránka";
				}
				else
				{
					echo "Editace stránky: $instanceAktualniStranky->id";
				}
				echo "</h1></div>";

				?>
				<form method="post">
					<div class="form-floating">
						<input type="text" name="id" id="id" class="form-control" value="<?php echo htmlspecialchars($instanceAktualniStranky->id) ?>" placeholder = "Id">
						<label for="id">Id</label>
					</div>

					<div class="form-floating">
						<input type="text" name="titulek" id="titulek" class="form-control" value="<?php echo htmlspecialchars($instanceAktualniStranky->titulek) ?>" placeholder = "Titulek">
						<label for="titulek">Titulek</label>
					</div>

					<div class="form-floating">
						<input type="text" name="menu" id="menu" class="form-control" value="<?php echo htmlspecialchars($instanceAktualniStranky->menu) ?>" placeholder = "Menu">
						<label for="menu">Menu</label>
					</div>

					<textarea id="obsah" name="obsah" cols="80" rows="15"><?php
					echo htmlspecialchars($instanceAktualniStranky->getObsah());
					?></textarea>
					<br>
					<button class= 'btn btn-primary' name="ulozit"><i class="fa-solid fa-floppy-disk"></i> Uložit</button>
				</form>
				<script src="vendor/tinymce/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
				<script>
					tinymce.init({
						selector: '#obsah',
						language: 'cs',
						language_url: '<?php echo dirname($_SERVER["PHP_SELF"]); ?>/vendor/tweeb/tinymce-i18n/langs/cs.js',
						height: '50vh',
						entity_encoding: 'raw',
						verify_html: false,
						content_css: [
							'css/reset.css',
							'css/section.css',
							'css/style.css',
							'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css',
							'https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap',
						],
						body_id: 'content',
						plugins: 'advlist anchor autolink charmap code colorpicker contextmenu directionality emoticons fullscreen hr image imagetools insertdatetime link lists nonbreaking noneditable pagebreak paste preview print save searchreplace tabfocus table textcolor textpattern visualchars',
						toolbar1: 'insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor',
						toolbar2: 'link unlink anchor | fontawesome | image media | responsivefilemanager | preview code',
						external_plugins: {
							'responsivefilemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
						},
						external_filemanager_path: "<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/filemanager/",
						filemanager_title: "File manager",
					});
				</script>
				<?php
			}
			echo "</main>";
		}
		?>
	</div>
</body>
</html>