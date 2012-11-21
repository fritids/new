<?php
class ecpCoupons {

    protected $db;
    protected $query;
    protected $qresult;
    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    private function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }

    private function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function removeCoupon($coupon_id) {
    	if($this->db->query("SELECT * FROM wp_ecp_coup_prod WHERE coupon_id=$coupon_id")){
        $this->query = "DELETE a,b FROM wp_ecp_coup_prod as a LEFT JOIN wp_ecp_coupons AS b ON a.coupon_id = b.coupon_id WHERE a.coupon_id=$coupon_id";
        $this->qresult = $this->db->query($this->query);
    	}
    	else{
    		$this->query = "DELETE FROM wp_ecp_coupons WHERE coupon_id=$coupon_id";
    		$this->qresult = $this->db->query($this->query);
    	}
        return $this->qresult;
    }
    
    public function disableCoupon($coupon_id){
    	  	
        $this->query = "UPDATE wp_ecp_coupons SET coupon_enabled=0 WHERE coupon_id=$coupon_id LIMIT 1";
        echo $this->query;
        $this->qresult = $this->db->query($this->query);
        return $this->qresult;
    }
    
   public function enableCoupon($coupon_id){
    	  	
        $this->query = "UPDATE wp_ecp_coupons SET coupon_enabled=1 WHERE coupon_id=$coupon_id LIMIT 1";
        echo $this->query;
        $this->qresult = $this->db->query($this->query);
        return $this->qresult;
    }

    
    
    public function listAllProductsWithCoupons() {
        //moze malce da se potsredi kverivo
        $this->query = "SELECT DISTINCT * FROM {$this->db->posts} INNER JOIN wp_ecp_coup_prod ON {$this->db->posts}.ID = wp_ecp_coup_prod.product_id INNER JOIN wp_ecp_coupons ON wp_ecp_coup_prod.coupon_id = wp_ecp_coupons.coupon_id ORDER BY ID";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }
    
     public function listProductsWithCoupons_edit($coupon_id) {
        //moze malce da se potsredi kverivo
        $this->query = "SELECT * FROM wp_ecp_coup_prod INNER JOIN {$this->db->posts} ON wp_ecp_coup_prod.product_ID = {$this->db->posts}.ID INNER JOIN wp_ecp_coupons ON wp_ecp_coupons.coupon_id = wp_ecp_coup_prod.coupon_id WHERE wp_ecp_coup_prod.coupon_id=$coupon_id ORDER BY ID";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }
    
     public function listProductsWithCoupons_edit_not_in_coupon($coupon_id) {
        //moze malce da se potsredi kverivo
        $this->query = "SELECT DISTINCT {$this->db->posts}.ID,{$this->db->posts}.post_title FROM wp_ecp_coup_prod INNER JOIN {$this->db->posts} ON wp_ecp_coup_prod.product_ID = {$this->db->posts}.ID INNER JOIN wp_ecp_coupons ON wp_ecp_coupons.coupon_id = wp_ecp_coup_prod.coupon_id WHERE wp_ecp_coup_prod.coupon_id != $coupon_id ORDER BY ID";
		$this->query = "SELECT DISTINCT * FROM {$this->db->posts} WHERE post_type='ecpproduct' ORDER BY ID";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }
    
    

    public function listAllProductsJoined() {
        $this->query = "SELECT DISTINCT * FROM {$this->db->posts} LEFT JOIN wp_ecp_coup_prod ON {$this->db->posts}.ID = wp_ecp_coup_prod.product_id WHERE post_type='ecpproduct'";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }
    
    public function insertProduct_in_Coupon($coupon_id,$product_id){
    	$this->query = "INSERT INTO wp_ecp_coup_prod (coupon_id,product_id) VALUES ($coupon_id,$product_id)";
    	$this->qresult = $this->db->query($this->query);
    	return $this->qresult;
    }
    
    public function lastInsert(){
    	return $this->db->insert_id;
    }
    
    public function listAllProducts(){
        $this->query = "SELECT * FROM {$this->db->posts} WHERE post_type='ecpproduct'";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }

    public function insertCoupon($couponcode,$coupon_price,$coupon_times,$validity_time_from,$validity_time_till){
    	$this->query = "INSERT INTO wp_ecp_coupons (coupon_code,coupon_price,coupon_usage,coupon_startdate,coupon_enddate) VALUES ('".$couponcode."','".$coupon_price."','".$coupon_times."','".$validity_time_from."','".$validity_time_till."')";
    	$this->qresult = $this->db->query($this->query);
    	return $this->qresult;
    }
    
    public function deleteProduct_From_Coupon($coupon_id,$product_id){
    	$this->query = "DELETE FROM wp_ecp_coup_prod WHERE product_id=$product_id and coupon_id=$coupon_id LIMIT 1";
    	$this->qresult = $this->db->query($this->query);
    	return $this->qresult;
    }
    
    
    public function listAllCoupons() {
        $this->query = "SELECT * FROM wp_ecp_coupons";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }
    
    public function listCoupon($coupon_id){
    	  $this->query = "SELECT * FROM wp_ecp_coupons where coupon_id=$coupon_id LIMIT 1";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }

    public function deleteProductFromCoupon($coupon_id,$product_id){
    	  $this->query = "DELETE FROM wp_ecp_coup_prod where coupon_id=$coupon_id and product_id=$product_id";
        $this->qresult = $this->db->query($this->query);
        return $this->qresult;
    	
    }
    
    public function updateCoupon($coupon_id,$coupon_code,$coupon_price,$coupon_times,$validity_time_from,$validity_time_till){
    	$this->query = "UPDATE wp_ecp_coupons SET coupon_code='".$coupon_code."',coupon_price=$coupon_price,coupon_usage=$coupon_times,coupon_startdate='".$validity_time_from."',coupon_enddate='".$validity_time_till."' WHERE coupon_id=$coupon_id";
        
    	$this->qresult = $this->db->query($this->query);
        return $this->qresult;
    }
    
    public function listAllProductsWithoutCoupons() {
        $this->query = "SELECT * FROM {$this->db->posts} INNER JOIN wp_ecp_coup_prod ON {$this->db->posts}.ID != wp_ecp_coup_prod.product_id INNER JOIN wp_ecp_coupons";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }
    
    public function decreaseCouponUsage($coupon_id){
    	$this->query = "UPDATE wp_ecp_coupons SET coupon_usage=coupon_usage-1 where coupon_id=$coupon_id";
        $this->qresult = $this->db->query($this->query);
        return $this->qresult;
    }
    
    public function listCouponbyProduct($product_id){
    	  $this->query = "SELECT a.coupon_id,a.coupon_code,a.coupon_price,a.coupon_usage,a.coupon_enddate,b.product_id FROM wp_ecp_coupons AS a INNER JOIN wp_ecp_coup_prod as b ON a.coupon_id = b.coupon_id where b.product_id=$product_id";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }
    
    public function checkCouponEndDate($coupon_id){
    	  $this->query = "SELECT * FROM wp_ecp_coupons where b.coupon_id=$coupon_id";
    	  $this->qresult = $this->db->get_results($this->query, ARRAY_A);
    	 //proverka da se naprae
    	  
    	  
    	  
    }

}
?>

