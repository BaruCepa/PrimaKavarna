$( "#stranky" ).sortable({
	update: () => {
		const sortedIDs = $( "#stranky" ).sortable( "toArray" );
		console.log(sortedIDs);

		$.ajax({
			url: "admin.php",
			data: {
				"poradi" : sortedIDs,
			}
		});
	}
});

$("#stranky .smazat").click((udalost) => {
	if (confirm("Opravdu chcete zvolenou stránku smazat?") == false)
	{
		// prerusit mazani a zachovat stranku (nenavstivit odkaz mazani)
		udalost.preventDefault();
	}
});