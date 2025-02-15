<?php
if(asset($_POST['action'])){
    $output = '';

    if($_POST['action'] == 'fetchdata'){
       function allevents(){
        $query = "SELECT * FROM events_db";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }  
    }
   
}
 