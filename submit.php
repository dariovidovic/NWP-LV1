<?php
include 'DiplomskiRadovi.php';
$diplomskiRadovi = new DiplomskiRadovi([]);
if (isset($_POST['submitButton'])) {
    /*-----------Dohvacanje rednog broja iz forme-----------*/
    $redni_broj = $_POST['redni_broj'];
    
    /*-----------Provjera je li korisnikov unos broj te je li izmedju 2 i 6-----------*/
    if (is_numeric($redni_broj) && ($redni_broj>=2 && $redni_broj<=6)) {
        $response = $diplomskiRadovi->create($redni_broj);
        if (is_array($response) && !empty($response)) {
            /*-----------Pohrana svakog objekta iz polja u bazu podataka-----------*/
                foreach ($response as $diplomskiRad) {
                $diplomskiRad->save();
            }
            
        }
        /*-----------Ispis atributa diplomskog rada-----------*/
        foreach($response as $diplomskiRad){
            echo "Naziv rada: " . $diplomskiRad->naziv_rada . "<br>";
            echo "Tekst rada: " . $diplomskiRad->tekst_rada . "<br>";
            echo "Link rada: " . $diplomskiRad->link_rada . "<br>";
            echo "OIB tvrtke: " . $diplomskiRad->oib_tvrtke . "<br><br>";
        }
        unset($_POST);
    } else {
        echo "Kriva unesena vrijednost";
    }

}

if (isset($_POST['fetchButton'])) {
    $diplomskiRadovi->read();
}

?>