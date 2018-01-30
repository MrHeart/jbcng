<?php
class AdminModel extends CI_Model{

    public function __construct(){
        $this->load->database();
    }

    var $table = 'user';
    var $column = array('surname','other_names','email','phone','address','state');
    var $order = array('id' => 'asc');

    /** =============================================Dashboard =================================*/
    public function userTotal(){
        $this->db->select('*');
        $this->db->from('user');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function subjectTotal(){
        $this->db->select('*');
        $this->db->from('subject');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function questionTotal(){
        $this->db->select('*');
        $this->db->from('question');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function adminTotal(){
        $this->db->select('*');
        $this->db->from('admin');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function usersLagos(){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('state', 'Lagos');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }


    public function usersOgun(){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('state', 'Ogun');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function usersOsun(){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('state', 'Osun');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function usersEkiti(){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('state', 'Ekiti');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function usersOyo(){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('state', 'Oyo');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }
    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column as $item)
        {
            if($_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }

        if(isset($_POST['order']))
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }


    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();

        return $query->row();
    }

    /** =============================================Questions, Departments and Subjects =================================*/
 public function CreateDepartment($name){
     $data = array(
         'name' => $name
     );
     $this->db->insert('department', $data);
 }

    public function CreateSubject($name, $departmentId){
        $data = array(
            'name' => $name,
            'department_id' => $departmentId
        );
        $this->db->insert('subject', $data);
    }

    public function CreateQuestion($question, $A, $B, $C, $D, $answer, $subject){
        $data = array(
            'question' => $question,
            'A' => $A,
            'B' => $B,
            'C' => $C,
            'D' => $D,
            'answer' => $answer,
            'subject_id' => $subject
        );
        $this->db->insert('question', $data);
    }

    public function GetDepartments($id=NULL){
        if($id != null){
            $this->db->from('department');
            $this->db->where('id',$id);
            $query = $this->db->get();
            return $query->result_object();
        }
        $query = $this->db->get('department');
        return $query->result_object();

    }

    public function GetSubjects($departmentId=null){
        if($departmentId != null){
            $this->db->from('subject');
            $this->db->where('department_id',$departmentId);
            $query = $this->db->get();
            return $query->result_object();
        }
        $query = $this->db->get('subject');
        return $query->result_object();

    }



    public function bookTotal(){
        $this->db->select('*');
        $this->db->from('user_audiobook');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function authorTotal(){
        $this->db->select('*');
        $this->db->from('app_user');
        $this->db->where('role', 'author');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;

    }

    public function narratorTotal(){
        $this->db->select('*');
        $this->db->from('app_user');
        $this->db->where('role', 'narrator');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;

    }


    public function activityTotal(){
        $this->db->select('*');
        $this->db->from('activity');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    /** ================================================= Users ================================= */
    public function all_users(){
        $this->db->select('*');
        $query = $this->db->get('user');
        $result = $query->result_array();
        return $result;
    }
    public function create()
    {
        $data = array(
            'surname' => $this->input->post('surname'),
            'other_names' => $this->input->post('other_names'),
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'password' => $this->input->post('password'),
            'address' => $this->input->post('address'),
            'state' => $this->input->post('state'),
            'lga' => $this->input->post('lga')
        );
        $query = $this->db->insert('user', $data);
        if($query === true){
            return true;
        }else{
            return false;
        }
    }
    public function edit($id)
    {
        if($id){
            $data = array(
                'surname' => $this->input->post('edit_surname'),
                'other_names' => $this->input->post('edit_other_names'),
                'email' => $this->input->post('edit_email'),
                'phone' => $this->input->post('edit_phone'),
                'password' => $this->input->post('edit_password'),
                'address' => $this->input->post('edit_address'),
                'state' => $this->input->post('edit_state'),
                'lga' => $this->input->post('edit_lga')
            );
            $this->db->where('id', $id);
            $query = $this->db->update('user', $data);
            if($query === true){
                return true;
            }else{
                return false;
            }
        }


    }

    public function create_users($data){

        $id = $data['id'];
        if($id > 0){
            $this->db->where("id". $id);
            $this->db->update("user", $data);
        }else{
            $this->db->insert("user", $data);
        }
        if($this->db->affected_row() > 0){
            return true;
        }else{
            return false;
        }
    }
    function viewUsers($id)
    {
        $this->db->select('*');
        $this->db->from('user');
        //$this->db->join('activity'); // will continue this later
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function search($value){
        $this->db->like("surname", $value);
        $this->db->or_like("email", $value);
        $this->db->order_by("id");
        $query = $this->db->get("user");
        return $query->result();

    }
    function delete_user($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
    }
    public function fetchUserModel($id = null)
    {
        if($id){
            $this->db->select('*');
            $this->db->from('user');
            $this->db->where('id', $id);
            $query = $this->db->get();
            return $query->row_array();
        }

        $this->db->select('*');
        $query = $this->db->get('user');
        $result = $query->result_array();
        return $result;

    }

    /** ================================================= Admins ================================= */
    public function AddAdmin(){
        $data = array(
            'admin_name' => $this->input->post('fullname'),
            'admin_email' => $this->input->post('name'),
            'admin_password' => $this->input->post('password'),
            'signup_date' => 'Y-m-d H:i:s'

        );

        $query = $this->db->insert('admin', $data);
        return $this->db->insert_id();
    }

    public function AdminAccess($adminId){
        if(isset($_POST["authors"])){
            $data = array(
                'user_id' => $adminId,
                'menu_name' => 'Manage Authors',
                'menu_url' => 'Authors/Manage',
                'menu_parent' => 'Authors',
                'accessible' => '1'

            );
            $this->db->insert('admin_menus', $data);
        }

        if(isset($_POST["narrators"])){
            $data = array(
                'user_id' => $adminId,
                'menu_name' => 'Manage Narrators',
                'menu_url' => 'Narrators/Manage',
                'menu_parent' => 'Narrators',
                'accessible' => '1'

            );
            $this->db->insert('admin_menus', $data);
        }

        if(isset($_POST["payments"])){
            $data = array(
                'user_id' => $adminId,
                'menu_name' => 'Payments',
                'menu_url' => 'Payments/Index',
                'menu_parent' => 'Payments',
                'accessible' => '1'

            );
            $this->db->insert('admin_menus', $data);
        }

        if(isset($_POST["support"])){
            $data = array(
                'user_id' => $adminId,
                'menu_name' => 'Support',
                'menu_url' => 'Support/Ticket',
                'menu_parent' => 'Support',
                'accessible' => '1'

            );
            $this->db->insert('admin_menus', $data);
        }

        if(isset($_POST["editor"])){
            $data = array(
                'user_id' => $adminId,
                'menu_name' => 'Editor',
                'menu_url' => 'Editor/Index',
                'menu_parent' => 'Editor',
                'accessible' => '1'

            );
            $this->db->insert('admin_menus', $data);
        }

        if(isset($_POST["settings"])){
            $data = array(
                'user_id' => $adminId,
                'menu_name' => 'Settings',
                'menu_url' => 'Settings/Notifications',
                'menu_parent' => 'Settings',
                'accessible' => '1'

            );
            $this->db->insert('admin_menus', $data);
        }
    }

    /** =============================================General / Common =================================*/
    public function activity_log($activity, $userId)
    {
        $data = array(
            'activity' => $activity,
            'userId' => $userId,
            'date' => 'Y-m-d H:i:s'

        );
        $this->db->insert('activity', $data);
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('app_user');
    }
}