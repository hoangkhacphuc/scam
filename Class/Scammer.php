<?php

class Scammer
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }

    public function createScammer($name, $phone, $card_number, $bank, $content, $name_auth, $phone_auth, $victim, $image)
    {
        
        $result = $this->db->insert('scammer', array(
            'name' => $name,
            'phone' => $phone,
            'card_number' => $card_number,
            'bank' => $bank,
            'content' => $content,
            'name_auth' => $name_auth,
            'phone_auth' => $phone_auth,
            'victim' => $victim
        ));
        
        if (!$result)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ));
            return;
        }

        for ($i=0; $i<count($image); $i++) {
            $this->db->insert('evidence', array(
                'url' => $image[$i],
                'scammer_id' => $result
            ));
        }

        echo json_encode(array(
            'status' => true,
            'message' => 'Tố cáo thành công'
        ));
    }

    public function search($target)
    {
        $result = $this->db->query('SELECT * FROM scammer WHERE `approved` = "1" AND ( name LIKE "%'.$target.'%" OR phone LIKE "%'.$target.'%" OR card_number LIKE "%'.$target.'%" OR bank LIKE "%'.$target.'%") ');
        $result = $this->db->getResultArray($result);
        return $result;
    }

    public function getListScammer()
    {
        $result = $this->db->select('scammer', "`approved` = '0'");
        return $result;
    }

    public function accept($id)
    {
        $result = $this->db->select('scammer', "`id` = '$id' AND `approved` = '0'");
        if (!$result || count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy đơn tố cáo'
            ));
            return;
        }

        $result = $this->db->update('scammer', array(
            'approved' => 1,
            'approved_by' => $_SESSION['User_ID']
        ), "`id` = '$id'");

        if (!$result)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ));
            return;
        }

        echo json_encode(array(
            'status' => true,
            'message' => 'Đã duyệt tố cáo'
        ));
    }

    public function delete($id)
    {
        $result = $this->db->select('scammer', "`id` = '$id'");
        if (!$result || count($result) == 0)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Không tìm thấy đơn tố cáo'
            ));
            return;
        }

        $result = $this->db->delete('evidence', "`scammer_id` = '$id'");
        if (!$result)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ));
            return;
        }

        $result = $this->db->delete('scammer', "`id` = '$id'");
        if (!$result)
        {
            echo json_encode(array(
                'status' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại'
            ));
            return;
        }

        echo json_encode(array(
            'status' => true,
            'message' => 'Đã xóa tố cáo'
        ));
    }

    public function getProfileScammerByID($id)
    {
        $result = $this->db->select('scammer', "`id` = '$id'");
        if (!$result || count($result) == 0)
        {
            return array();
        }

        $img_result = $this->db->select('evidence', "`scammer_id` = '$id'");
        $result = array_merge($result[0], array('image' => array_column($img_result, 'url')));

        return $result;
    }
}

?>