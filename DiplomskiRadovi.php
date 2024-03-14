<?php
include 'simple_html_dom.php';

interface iRadovi {
    public function create($redni_broj);
    public function save();
    public function read();
}

class DiplomskiRadovi implements iRadovi {
    public $_userId = NULL;
    public $naziv_rada = NULL;
    public $tekst_rada = NULL;
    public $link_rada = NULL;
    public $oib_tvrtke = NULL;


    function __construct($data) {
        $this->_userId = uniqid();
        $this->naziv_rada = $data['naziv_rada'] ?? null;
        $this->tekst_rada = $data['tekst_rada'] ?? null;
        $this->link_rada = $data['link_rada'] ?? null;
        $this->oib_tvrtke = $data['oib_tvrtke'] ?? null;
    }

    function save(){
        /*-----------Spajanje i provjera postojanosti veze s bazom podataka-----------*/
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "radovi";

        $spoj = new mysqli($servername, $username, $password, $dbname);

        if ($spoj->connect_error) {
            die("Došlo je do greške: " . $spoj->connect_error);
        }
        /*-----------------------------------------------------------------------------*/

        $_userId = $this->_userId;
        $naziv_rada = $this->naziv_rada;
        $tekst_rada = $this->tekst_rada;
        $link_rada = $this->link_rada;
        $oib_tvrtke = $this->oib_tvrtke;
        
        $sql = "INSERT INTO `diplomski_radovi`(`naziv_rada`, `tekst_rada`, `link_rada`, `oib_tvrtke`) VALUES ('$naziv_rada','$tekst_rada','$link_rada','$oib_tvrtke')";
        $stmt = $spoj->prepare($sql);
        $rezultat= $stmt->execute();
    
    }

    function read(){

        /*-----------Spajanje i provjera postojanosti veze s bazom podataka-----------*/
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "radovi";

        $spoj = new mysqli($servername, $username, $password, $dbname);

        if ($spoj->connect_error) {
            die("Došlo je do greške: " . $spoj->connect_error);
        }
        /*-----------------------------------------------------------------------------*/

        $sql = "SELECT * FROM `diplomski_radovi`";
        $stmt = $spoj->query($sql);
       
        /*-----Provjera je li postoje objekti u bazi podataka-----*/
        /*-----Ako postoje diplomski radovi (objekti/reci) u bazi podataka, prolazi se kroz svaki redak pojedinacno-----*/
        /*-----i ispisuju se njegove vrijednosti-----*/
        if ($stmt->num_rows > 0) { 
            while($row = $stmt->fetch_assoc()) {
                echo "Naziv rada: " . $row["naziv_rada"]. "<br>";
                echo "Tekst rada: " . $row["tekst_rada"]. "<br>";
                echo "Link rada: " . $row["link_rada"]. "<br>";
                echo "OIB tvrtke: " . $row["oib_tvrtke"]. "<br>";
                echo "<br>";
            }
        }
        else {
            echo "No data in the DB!";
        }
    }

    function create($redni_broj){
            $url = "https://stup.ferit.hr/zavrsni-radovi/page/$redni_broj/";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            $response = curl_exec($curl);
            curl_close($curl);

            $dom = new simple_html_dom();
            $dom->load($response);

            $diplomskiRadovi = [];

            foreach($dom->find('article') as $article) {
                
                foreach($article->find('ul.slides img') as $img) {
                }
            
                /*-------------Otvaranje linka rada kako bi se pronašao tekst-------------*/
                foreach($article->find('h2.entry-title a') as $link) {
                $curl = curl_init($link->href);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
                $newResponse = curl_exec($curl);
                curl_close($curl);
                $newDom = new simple_html_dom();
                $newDom->load($newResponse);
                /*------------------------------------------------------------------------*/

                foreach($newDom->find('.post-content') as $text) {
                }
                /*-----Spremanje svih extractanih html podataka sa stranice u $data-----*/
                    $data = [
                        'naziv_rada' => $link->plaintext,
                        'tekst_rada' => $text->plaintext,
                        'link_rada' => $link->href,
                        'oib_tvrtke' => preg_replace('/[^0-9]/', '', $img->src)
                    ];
                /*-----Instanciranje novog objekta te spremanje u polje diplomskih radova-----*/
                    $diplomskiRad = new DiplomskiRadovi($data);
                    $diplomskiRadovi[] = $diplomskiRad;
                
                }
                
            }
            return $diplomskiRadovi;
                 
    }

}


?>

