<?php
class Reservation{
    // Member variables (properties)
    public $zone_num;
    public $phone;
    public $cancellation = false;
    public $fee;
    public $date_reserved;
    public $confirmationNumber;
    private $local_conn = new mysqli("127.0.0.1", "root", "mySQLmySQL", "ParkingReservations");



    // Constructor method
    public function __construct($zone_num, $phone, $date, $rate) {
        $this->zone_num = $zone_num;
        $this->phone = $phone;
        $this->cancellation = 0;
        $this->date_reserved = $date;
        $this->fee = $rate;
    }

    public function insertReservation(): bool{
       if(isSet($this->zone_num) && isSet($this->phone) && isSet($this->date_reserved)){
            $this->gcf();
            $user_reservation_query = 
            "INSERT INTO RESERVATION(PHONE, CANCELLATION, DATE_RESERVED, FEE, ZONE_NUM, CONFIRMATION_NUM)
            VALUES('$this->phone', false, '$this->date_reserved', 
            (SELECT RATE FROM zone WHERE '$this->date_reserved' = zone.date), '$this->confirmationNumber')";
            $b = $this -> local_conn -> execute_query($user_reservation_query);
            if($b) echo "<p> Reservation Created Successfully </p>";
            else echo "<p> Reservation Failed to Create! </p>";
            return $b;
       } else {
            echo "<p> Reservation Failed to Create! </p>";
       }
        
       return true;
    }

    public function cancelReservation(): bool{
       $update_cmd =  "UPDATE reservation r SET status = true 
        WHERE $this->confirmationNumber = r.confirmation_number
        OR '$this->phone' = r.phone";
        $b = $this -> local_conn -> execute_query($update_cmd);
        if($b) echo "<p> Reservation Cancelled successfully! </p>";
        else echo "<p> Reservation failed to cancel! </p>";
        return $b;
    }


    public function verify_date($int):bool{
       
        $timestamp = strtotime($this->date_reserved);
        $usr_date = date('Y-m-d', $timestamp);
        $comp_date = (new DateTime())->sub(new DateInterval("P{$int}D"))->format('Y-m-d');
        return $usr_date < $comp_date;
    }


    private function gcf(){
        $count = 1;
        while($count > 0){
            $this->confirmationNumber = rand(0, 2147483647);
            //Code to check if confirmation number exists. 
            $query_confirmation_number = "SELECT COUNT(*) AS count
                FROM reservations
                WHERE CONFIRMATION_NUM = '$this->confirmationNumber'";
            $result = $this->local_conn->query($query_confirmation_number);
            
            if(!$result){
                die("Error in query preparation: ");
            }

            $row = $result->fetch_assoc();
            $count = $row['count'];
        }
    }

}
?>
