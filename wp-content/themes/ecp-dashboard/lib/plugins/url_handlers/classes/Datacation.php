<?php
if( !class_exists( 'WP_Http' ) )
    include_once( ABSPATH . WPINC. '/class-http.php' );

class Datacation{
    
    ### DO NOT FORGET ###
    /* to run this script on DB:
     * ALTER TABLE `wp_teacherstudent` ADD `course` VARCHAR( 15 ) NULL DEFAULT NULL 
     */
    
    private $request = null;
    private $url = 'https://app1.datacation.ws/edge/?username=';
    private $uname = 'GqkRwhmfPG24pJV8nU3H';
    private $pass = 'NjbKk25ccM8wpyZNvDdcxYcHwEVRr4';
    private $headers = array();
    private $result = null;
    private $online_package = 12; //12 months. Just coincidence about the code.
    
    /**
     *
     * @$user_data array
     * Array with the following elements:
     *  - ID
     *  - userType
     *  - authEmail
     *  - userName
     *  - authID
     *  - FirstName
     *  - LastName
     */
    private $user_data = array();
    
    /**
     *
     * @$students array
     * Array with course => student-list form.
     * Student-list is an array of objects. Each object has the following attributes:
     *  - ID
     *  - LastName
     *  - FirstName
     *  - Email
     */
    private $students = array();
    
    /**
     * 
     * Constructor
     * @param string $user user which is going to be searched.
     */
    
    public function __construct($user){
        $this->headers = array('Authorization' => 'Basic '.base64_encode("$this->uname:$this->pass"));
        $this->request = new WP_Http;
        $this->result = $this->request->request( $this->url.$user , array( 'method' => 'POST', 'sslverify' => false,'headers' => $this->headers ) );
        if(is_wp_error($this->result) OR $this->result['hasAccess']===0){
            //do something on login error or user has no access...
            
        }else{
            $this->result = json_decode($this->result['body']);
            $data = $this->result->authServer;
            $this->user_data = array(
                'ID' => NULL,
                'userType' => $data->userType,
                'authEmail' => $data->authEmail,
                'userName' => $data->userName,
                'authID' => $data->authID,
            );
            if($this->user_data['userType']=='skdTeacher'){
                $user_information = $this->result->teacherInformation;
                $this->user_data['FirstName'] = $user_information->FirstName;
                $this->user_data['LastName'] = $user_information->LastName;
                //STUDENTS
                $courses = $user_information->TeacherCourses;
                if(is_array($courses)){
                    foreach($courses as $course){
                        $key = $course->Course;
                        $students_aux = $course->Students;
                        if(is_array($students_aux) AND sizeof($students_aux)>0){
                            foreach($students_aux as $cur_student){
                                $this->students[$key][] = $cur_student;
                            }
                        }
                    }
                }
            }elseif($this->user_data['userType']=='student'){
                $user_information = $this->result->studentInformation;
                $this->user_data['FirstName'] = $user_information->FirstName;
                $this->user_data['LastName'] = $user_information->LastName;
            }
        }
   	}
   	
    public function get_userType(){
        return $this->user_data['userType'];
    }
    
    public function get_userData(){
        return $this->user_data;
    }
    
    public function get_students(){
        return $this->students;
    }
    
    public function save_data(){
        self::_create_user(
                $this->user_data['LastName'],
                $this->user_data['FirstName'],
                $this->user_data['authEmail'],
                $this->user_data['authID'],
                $this->user_data['userType'],
                TRUE
                );
    }
    
    /**
     * This function must be called after save_data, or else, students will not be related to their corresponding teacher.
     */
    public function save_students(){
        if(sizeof($this->students)>0 AND $this->user_data['userType']=='skdTeacher'){
            foreach($this->students as $course => $student){
                self::_create_user(
                    $student->LastName,
                    $student->FirstName,
                    $student->Email,
                    $student->ID,
                    'student',
                    FALSE,
                    $this->user_data['ID'],
                    $course
                    );
            }
        }
    }
    
    /**
     * Creates a user based on the parameters
     * @param string $last_name - User last name
     * @param string $first_name - User first name
     * @param string $user_email - User email
     * @param string $casenex_id - User datacation / casenex ID
     * @param string $uType - User type (student or teacher)
     * @param boolean $update_id - $this->user_data[ID] must be updated???
     * @param string $teacher_id - when creating students, teacher_id to be related.
     * @param string $course_id - when creating students, course to be related.
     */
    private function _create_user($last_name,$first_name,$user_email,$casenex_id, $uType, $update_id, $teacher_id = NULL, $course_id = NULL){
        $userdata = array(
            'last_name'=>$last_name,
            'first_name'=>$first_name,
            'user_email'=>$user_email,
            'casenex_id'=>$casenex_id,
        );
        
        $user_id = 0;
            
        if ( ! empty( $userdata['last_name'] ) ) {
            $std = new WP_User_Query( array( 'search' => $userdata['user_email'], 'search_columns'=>'user_email' ) );
            
            if ( !empty( $std->results ) ) {
                foreach($std->results as $current){
                    //getting current user id
                    $user_id = $current->ID;
                    break;
                }
            }
        }
        
        if($user_id == 0){
            $userdata['user_login'] = $userdata['user_email'];
            $userdata['user_pass'] = wp_generate_password( 12, false );
            $userdata['user_registered'] = date('Y-m-d H:i:s');
            $userdata['user_nicename'] = trim($userdata['first_name']." ".$userdata['last_name']);
            $userdata['display_name'] = trim($userdata['first_name']." ".$userdata['last_name']);
			
			// Create custom user meta
			$usermeta['_IDGL_elem_Username'] = $userdata['user_login'];
			$usermeta['_IDGL_elem_FirstName'] = $userdata['first_name'];
			$usermeta['_IDGL_elem_LastName'] = $userdata['last_name'];
			$usermeta['_IDGL_elem_Nickname'] = $userdata['user_nicename'];
			$usermeta['_IDGL_elem_Email'] = $userdata['user_email'];
            if($uType=='skdTeacher'){
                $usermeta['_IDGL_elem_user_type'] = 'teacher';
            }else{
                $usermeta['_IDGL_elem_user_type'] = 'student';
            }
			$usermeta['_IDGL_elem_registration_date'] = time();
            
            $user_type = $usermeta['_IDGL_elem_user_type'];
            
            if($user_type == "student") {
				$usermeta['_IDGL_elem_userSubtype'] = serialize(array('online-student'));
                $usermeta['_IDGL_elem_ECP_user_order'] = serialize(array($this->online_package));
			}
            
            // Insert user
            $user_id = wp_insert_user($userdata);
            
            if($update_id)
                $this->user_data['ID'] = $user_id;
            
            if ( is_wp_error( $user_id ) ) {
                //SET ERRORS AND HANDLE WITH THEM
            } else {
                foreach ( $usermeta as $metakey => $metavalue ) {
                    $metavalue = maybe_unserialize( $metavalue );
                    update_user_meta( $user_id, $metakey, $metavalue );
                }
                
                if($user_type == "student") {
                    // order details metadata
                    global $wpdb;

                    $post_sql = "SELECT ID, post_name, meta_value
                                FROM wp_3_posts post
                                JOIN wp_3_postmeta meta ON post.ID = meta.post_id 
                                WHERE post.ID = $this->online_package
                                AND meta_key = 'universal_price'";

                    $product = $wpdb -> get_results($wpdb -> prepare($post_sql));
                    $orders_details = array();
                    
                    $product_as_array = array();
                    if( ! empty($product)){
                        foreach($product as $key => $product){
                            $product_as_array[$key]["id"] = $product->ID;
                            $product_as_array[$key]["type"] = $product->post_name;
                            $product_as_array[$key]["price"] = $product->meta_value;
                        }
                        $orders_details[time()]=$product_as_array;  //TODO: maybe all students must be created with an specific date, not with the current one.
                    }
                    update_user_meta($user_id, "_IDGL_elem_ECP_user_orders_details", $orders_details);
                    
                    //teacher - student - course relationship
                        if( ! is_null($teacher_id) AND ! is_null($course_id)){                            
                            $q = "INSERT IGNORE INTO wp_teacherstudent (student_ID,teacher_ID, course) VALUES ";
                            $q .= "($user_id, $teacher_id, $course_id)";
                            $wpdb->query($q);
                        }
                }
                update_user_option( $user_id, 'default_password_nag', true, true );
            }
        }else{
            //MAYBE UPDATE USER?
        }
    }
    
//    public function get_result(){
//        return $this->result;
//    }
   	
}
?>