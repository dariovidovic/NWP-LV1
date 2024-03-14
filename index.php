<?php include 'DiplomskiRadovi.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NWP-LV1</title>
</head>
<body>
    <!--Forma za unos rednog broja stranice, nakon klika na button, redni broj se sprema u POST['redni-broj'] u submit.php -->
    <!--te se prikazuju diplomski radovi sa stranice rednog broja na submit.php -->
    <form method="post" action="submit.php">
        <label for="redni_broj">Redni broj stranice: </label>
        <input type="number" id="redni_broj" name="redni_broj"><br>
        <input type="submit" name="submitButton" value="Submit"/><br> 
    </form> 

    <!--Forma za dohvaćanje diplomskih radova iz baze podataka, nakon klika na button u submit.php -->
    <!-- se dohvaćaju te prikazuju radovi iz baze podataka -->
    <form method="post" action="submit.php">
        <label for="fetch">Dohvati iz baze: </label>
        <input type="submit" name="fetchButton" value="Fetch"/><br> 
    </form> 
    <!-- Submit.php je dodan kako bi se poboljšalo rukovanje podacima iz forme -->
</body>
</html>