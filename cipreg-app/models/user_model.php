<?php 
class User_model extends CI_Model {

	function login($userinfo)
     {
         $data = array('username'=>$userinfo->username,
         			   'user_id'=>$userinfo->id, 
         			   'logged_in'=>TRUE);
         $this->session->set_userdata($data);                    //添加session数据
     }
     function get_by_username($username)
     {
         $this->db->where('username', $username);
         $query = $this->db->get('user');
         //return $query->row();                            //不判断获得什么直接返回
         if ($query->num_rows() == 1)
         {
             return $query->row();
         }
         else
         {
             return FALSE;
         }
     }
     function password_check($username, $password)
     {                
         if($user = $this->get_by_username($username))
         {
             return $user->password == $password ? TRUE : FALSE;
         }
         return FALSE;  //当用户名不存在时
     }
     function get_by_id($id){	
     	$this->db->where('id', $id);
        $query = $this->db->get('user');
        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        else
        {
            return FALSE;
        }
     }
     function logout()
     {
         if ($this->logged_in() === TRUE)
         {
             $this->session->sess_destroy();                //销毁所有session的数据

             return TRUE;
         }
         return FALSE;
     }
     public function logged_in()
     {
         if( $this->get_session('logged_in')===TRUE){
            return TRUE;
         }else{
            return 'You have not logged in.';
         }
     }
     public function get_session($data_name)
     {
         if (isset( $this->session->userdata[$data_name]))
            {return  $this->session->userdata[$data_name];}
            else {return null;}
     }
     function add_user($username, $password, $display_name)
     {
         $data = array('username'=>$username, 'password'=>$password,  
         	           'display_name'=>$display_name);
         $this->db->insert('user', $data);
         if ($this->db->affected_rows() > 0){return TRUE;}
         return FALSE;
     }
     function edit_user($id, $username, $password, $display_name)
     {
        $data = array('username' => $username,
                      'display_name' => $display_name);
        if ($password != ""){$data['password'] = $password;}
        $this->db->where('id', $id);
        $this->db->update('user', $data);
     }
}
