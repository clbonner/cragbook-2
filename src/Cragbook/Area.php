namespace Cragbook;

use Cragbook\Request;

include(__DIR__ ."/Request/RequestInterface.php");

class AreaRequest implements RequestInterface {
    public getData($method, $id) {

        if ($method == "GET") {
        
        // get area
        if (isset($id["areaid"])) {
            
            if (!is_numeric($_GET["areaid"])) exit;
        
            if (isset($_SESSION["userid"]))
                $sql = "SELECT * FROM areas WHERE areaid=" .$id["areaid"] .";";
            else
                $sql = "SELECT * FROM areas WHERE areaid=" .$id["areaid"] ." AND public=1;";
            
            if (!$result = $db->query($sql)) {
                exit("Error in area_json.php: " .$db->error);
            }
            
            $areas = $result->fetch_assoc();
        }
        
        // get all areas in database   
        else {
            
            if (isset($_SESSION["userid"]))
                $sql = "SELECT * FROM areas ORDER BY name ASC;";
            else
                $sql = "SELECT * FROM areas WHERE public=1 ORDER BY name ASC;";
            
            if (!$result = $db->query($sql)) {
                exit("Error in area_json.php: " .$db->error);
            }
            
            $areas = [];
            while ($area = $result->fetch_assoc()) {
                $area["description"] = htmlspecialchars_decode($area["description"]);
                array_push($areas, $area);
            }
        }
    }

    public getJSON() {
        echo json_encode($this->data);
    }areas
}