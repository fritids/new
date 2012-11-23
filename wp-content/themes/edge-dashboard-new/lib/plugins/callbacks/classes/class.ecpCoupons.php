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
        $this->query = "DELETE a,b FROM wp_ecp_coup_prod as a LEFT JOIN wp_ecp_coupons AS b ON a.coupon_id = b.coupon_id WHERE a.coupon_id=$coupon_id";
        $this->qresult = $this->db->query($this->query);
        return $this->qresult;
    }

    public function listAllProductsWithCoupons() {
        //moze malce da se potsredi kverivo
        $this->query = "SELECT * FROM {$this->db->posts} INNER JOIN wp_ecp_coup_prod ON {$this->db->posts}.ID = wp_ecp_coup_prod.product_id INNER JOIN wp_ecp_coupons ON wp_ecp_coup_prod.coupon_id = wp_ecp_coupons.coupon_id ORDER BY ID";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }

    public function listAllProductsJoined() {
        $this->query = "SELECT DISTINCT * FROM {$this->db->posts} LEFT JOIN wp_ecp_coup_prod ON {$this->db->posts}.ID = wp_ecp_coup_prod.product_id WHERE post_type='ecpproduct'";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);

        return $this->qresult;
    }
    public function listAllProducts(){
        $this->query = "SELECT * FROM {$this->db->posts} WHERE post_type='ecpproduct'";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }

    public function listAllCoupons() {
        $this->query = "SELECT * FROM wp_ecp_coupons";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }

    public function listAllProductsWithoutCoupons() {
        $this->query = "SELECT * FROM {$this->db->posts} INNER JOIN wp_ecp_coup_prod ON {$this->db->posts}.ID != wp_ecp_coup_prod.product_id INNER JOIN wp_ecp_coupons";
        $this->qresult = $this->db->get_results($this->query, ARRAY_A);
        return $this->qresult;
    }

}
?>
