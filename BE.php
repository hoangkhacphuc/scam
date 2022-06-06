<?php

session_start();
if (file_exists("../Class/Database.php")) {
    require_once "../Class/Database.php";
}
else require_once('./Class/Database.php');

if (file_exists("../Class/User.php")) {
    require_once "../Class/User.php";
}
else require_once('./Class/User.php');

if (file_exists("../Class/Scammer.php")) {
    require_once "../Class/Scammer.php";
}
else require_once('./Class/Scammer.php');

$controller = new Controller();

$action = "";
if (isset($_GET['api'])) {
    $action = $_GET['api'];
    if (method_exists("Controller", "API_".$action)) {
        $controller->{"API_".$action}();
    }
    else {
        echo json_encode(array(
            'status' => false,
            'message' => 'API không tồn tại'
        ));
    }
}

class Controller
{
    private $userModel;
    private $scammerModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->scammerModel = new Scammer();
    }

    public function API_Report()
    {
        if (!isset($_POST['name']) || !isset($_POST['phone']) || !isset($_POST['card_number']) || !isset($_POST['bank']) || !isset($_POST['content']) || !isset($_POST['name_auth']) || !isset($_POST['phone_auth']) || !isset($_POST['victim'])) {
            echo json_encode(array(
                'status' => 'false',
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $card_number = $_POST['card_number'];
        $bank = $_POST['bank'];
        $content = $_POST['content'];
        $name_auth = $_POST['name_auth'];
        $phone_auth = $_POST['phone_auth'];
        $victim = $_POST['victim'];
        $image = isset($_POST['image']) ? $_POST['image'] : '';

        // check empty
        if (empty($name) || empty($phone) || empty($card_number) || empty($bank) || empty($content) || empty($name_auth) || empty($phone_auth)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }
        if (!is_numeric($victim) || $victim < 0 || $victim > 1) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Nạn nhân không hợp lệ'
            ));
            return;
        }
        if (strlen($name) < 5 || strlen($name) > 150) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Tên người bị tố cáo không hợp lệ'
            ));
            return;
        }

        if (!$this->isPhoneNumber($phone)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Số điện thoại không hợp lệ'
            ));
            return;
        }

        if (!$this->isPhoneNumber($phone_auth)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Số điện thoại người xác thực không hợp lệ'
            ));
            return;
        }

        if (!is_numeric($card_number)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Số thẻ không hợp lệ'
            ));
            return;
        }

        $this->scammerModel->createScammer($name, $phone, $card_number, $bank, $content, $name_auth, $phone_auth, $victim, $this->explodeString($image));
    }

    public function API_Login()
    {
        if ($this->userModel->isLogin()) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Bạn đã đăng nhập'
            ));
            return;
        }

        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        $this->userModel->login($username, $password);
    }

    public function API_Logout()
    {
        $this->userModel->logout();
        header('Location: ./index.php');
    }

    public function API_DeleteUser()
    {
        if (!$this->userModel->isLogin()) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Bạn chưa đăng nhập'
            ));
            return;
        }

        if (!isset($_POST['id'])) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        $id = $_POST['id'];

        if (empty($id)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        if (!is_numeric($id)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'ID không hợp lệ'
            ));
            return;
        }

        if ($this->userModel->getUserType() == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Bạn không có quyền xóa người dùng này'
            ));
            return;
        }

        if ($id == $_SESSION['User_ID'])
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Bạn không thể xóa chính mình'
            ));
            return;
        }

        $this->userModel->deleteUser($id);
    }

    public function API_AcceptScammer()
    {
        if (!$this->userModel->isLogin()) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Bạn chưa đăng nhập'
            ));
            return;
        }

        if (!isset($_POST['id'])) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        $id = $_POST['id'];

        if (empty($id)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        if (!is_numeric($id)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'ID không hợp lệ'
            ));
            return;
        }
        $this->scammerModel->accept($id);
    }

    public function API_DeleteScammer()
    {
        if (!$this->userModel->isLogin()) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Bạn chưa đăng nhập'
            ));
            return;
        }

        if (!isset($_POST['id'])) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        $id = $_POST['id'];

        if (empty($id)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        if (!is_numeric($id)) {
            echo json_encode(array(
                'status' => false,
                'message' => 'ID không hợp lệ'
            ));
            return;
        }

        $this->scammerModel->delete($id);
    }

    public function API_CreateMember()
    {
        if (!$this->userModel->isLogin()) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Bạn chưa đăng nhập'
            ));
            return;
        }

        if (!isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['gender']) || !isset($_POST['birthday']) || !isset($_POST['address']) || !isset($_POST['role'])) {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ));
            return;
        }

        $name = $_POST['name'];
        $gender = strtolower($_POST['gender']);
        $birthday = $_POST['birthday'];
        $address = $_POST['address'];
        $role = $_POST['role'];
        $id = $_POST['id'];

        if ($gender != 'nam' && $gender != 'nữ')
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Giới tính không hợp lệ'
            ));
            return;
        }

        if (empty($id) || empty($name) || empty($gender) || empty($birthday) || empty($address) || empty($role))
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Vui lòng nhập đủ thông tin'
            ));
            return;
        }

        $gender = $gender == 'nam' ? 1 : 0; 
        if (!$this->checkDate($birthday))
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Ngày sinh không hợp lệ'
            ));
            return;
        }
        $birthday = $this->formatDate($birthday);
        if ($id == -1)
            $this->userModel->createMember($name, $gender, $birthday, $address, $role);
        else $this->userModel->updateMember($id, $name, $gender, $birthday, $address, $role);
    }

    public function isPhoneNumber($phone)
    {
        $pattern = '/^(0|\+84)(\s|\.)?((3[2-9])|(5[689])|(7[06-9])|(8[1-689])|(9[0-46-9]))(\d)(\s|\.)?(\d{3})(\s|\.)?(\d{3})$/';
        if (preg_match($pattern, $phone)) {
            return true;
        }
        return false;
    }

    public function explodeString($string)
    {
        $array = explode("|", $string);
        return $array;
    }

    public function getListUser()
    {
        return $this->userModel->getListUser();
    }

    public function getListScammer()
    {
        return $this->scammerModel->getListScammer();
    }

    public function getProfileScammerByID($id)
    {
        return $this->scammerModel->getProfileScammerByID($id);
    }

    public function search($search)
    {
        return $this->scammerModel->search($search);
    }

    public function checkDate($date)
    {
        $pattern = '/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/';
        if (preg_match($pattern, $date)) {
            return true;
        }
        return false;
    }

    public function formatDate($date)
    {
        $date = explode("/", $date);
        $date = $date[2] . "-" . $date[1] . "-" . $date[0];
        return $date;
    }

}

function isLogin()
{
    if (isset($_SESSION['User_ID'])) {
        return true;
    }
    return false;
}

function listUser()
{
    global $controller;
    return $controller->getListUser();
}


function listScammer()
{
    global $controller;
    return $controller->getListScammer();
}

function formatDate($date)
{
    $date = strtotime($date);
    return date('d/m/Y', $date);
}

function getProfileScammerByID($id)
{
    global $controller;
    return $controller->getProfileScammerByID($id);
}

function search($search)
{
    global $controller;
    return $controller->search($search);
}
?>