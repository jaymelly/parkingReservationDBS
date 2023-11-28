<?php
class Reservation{
    // Member variables (properties)
    public $zone_num;
    public $phone;
    public $cancellation = false;
    public $fee;
    public $date_reserved;
    public $confirmationNumber;
    private $local_conn;



    // Constructor method
    public function __construct($zone_num, $phone, $date, $rate, $conn) {
        $this->zone_num = $zone_num;
        $this->phone = $phone;
        $this->cancellation = false;
        $this->date_reserved = $date;
        $this->fee = $rate;
        $this->local_conn = $conn;
    }

    public function insertReservation(): bool{
       if(isSet($this->zone_num) && isSet($this->phone) && isSet($this->date_reserved)){
            
            $user_reservation_query = sprintf(
            "INSERT INTO RESERVATION(Confirmation_id, Zone_num, Phone, Cancelled, DATE_RESERVED, FEE)
            VALUES($this->confirmationNumber, $this->zone_num, $this->phone, false, '$this->date_reserved', 
            (SELECT RATE FROM ZONE z WHERE '$this->date_reserved' = z.Zone_date AND $this->zone_num = z.Zone_num)");
            //echo ($user_reservation_query);
            $b = mysqli_query($this->local_conn, $user_reservation_query);
            return $b;
       } else {
            echo "<p> Reservation Failed to Create! </p>";
       }
        
       return true;
    }

    public function setResConfirm($confirmation_num){
        $this -> confirmationNumber = $confirmation_num;
        $resCmd = "SELECT * FROM reservation r WHERE r.Confirmation_id = $confirmation_num";
        $result = mysqli_query($this->local_conn, $resCmd);
        if(!$result){
            die("Error in query preparation: id 47 ");
        }
        $values = $result -> fetch_array();
        $this->date_reserved = $values['DATE_RESERVED'];
        $this->fee = $values['FEE'];
        $this->zone_num = $values['ZONE_NUM'];
        $this->phone = $values['PHONE'];

    }

    public function cancelReservation(): bool{
       $update_cmd =  "UPDATE RESERVATION r SET Cancelled = true 
        WHERE $this->confirmationNumber = r.Confirmation_id
        OR $this->phone = r.Phone";

        echo $update_cmd;
        $result = mysqli_query($this->local_conn, $update_cmd);
       
        if(!$result){
            die("Error in query preparation: id 57 ");
        }
        return $result;
    }


    public function verify_date($int):bool{
       
        $timestamp = strtotime($this->date_reserved);
        $usr_date = date('Y-m-d', $timestamp);
        $comp_date = (new DateTime())->sub(new DateInterval("P{$int}D"))->format('Y-m-d');
        return $usr_date < $comp_date;
    }


    public function generateUniqueCnf(){
        $count = 1;
        while($count > 0){
            $this->confirmationNumber = rand(0, 2147483647);
            
            $queryConfirmationNumber = sprintf("SELECT COUNT(*) AS count FROM RESERVATION WHERE CONFIRMATION_NUM = %d", $this->confirmationNumber);
            
            $result = mysqli_query($this->local_conn, $queryConfirmationNumber);
            if(!$result){
                die("Error in query preparation: ");
            }
            $row = mysqli_fetch_assoc($result);
            $count = $row['count'];

        }
    }

}
?>
