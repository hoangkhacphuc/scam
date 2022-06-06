<?php

class User
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }

    public function isLogin()
    {
        if (isset($_SESSION['User_ID'])) {
            return true;
        }
        return false;
    }

    public function login($username, $password)
    {
        $password = md5($password);
        $result = $this->db->select('account', "`username` = '$username' AND `password` = '$password'");
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Tài khoản hoặc mật khẩu không chính xác'
            ));
            return;
        }
        $_SESSION['User_ID'] = $result[0]['id'];

        echo json_encode(array(
            'status' => true,
            'message' => 'Đăng nhập thành công'
        ));
        return;
    }

    public function getListUser()
    {
        $result = $this->db->select('account');
        return $result;
    }

    public function deleteUser($id)
    {
        $result = $this->db->select('account', "`id` = '$id'");
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Tài khoản không tồn tại'
            ));
            return;
        }
        $this->db->delete('account', "`id` = '$id'");
        echo json_encode(array(
            'status' => true,
            'message' => 'Xóa tài khoản thành công'
        ));
    }

    public function getUserType()
    {
        $result = $this->db->select('account', "`id` = '$_SESSION[User_ID]'");
        return $result[0]['user_type'];
    }

    public function logout()
    {
        session_destroy();
        return;
    }

    public function createMember($name, $gender, $birthday, $address, $role)
    {
        $result = $this->db->insert('account', array(
            'name' => $name,
            'gender' => $gender,
            'birthday' => $birthday,
            'address' => $address,
            'role' => $role
        ));
        if (!$result)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Có lỗi xảy ra'
            ));
            return;
        }
        echo json_encode(array(
            'status' => true,
            'message' => 'Thêm nhân viên thành công',
            'id' => $result
        ));
    }

    public function updateMember($id, $name, $gender, $birthday, $address, $role)
    {
        $result = $this->db->select('account', "`id` = $id");
        if (count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy nhân viên'
            ));
            return;
        }

        $result = $this->db->update('account', array(
            'name' => $name,
            'gender' => $gender,
            'birthday' => $birthday,
            'address' => $address,
            'role' => $role
        ), "`id` = $id");
        if (!$result)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Có lỗi xảy ra'
            ));
            return;
        }
        echo json_encode(array(
            'status' => true,
            'message' => 'Cập nhật thông tin nhân viên thành công'
        ));
    }
}
?>