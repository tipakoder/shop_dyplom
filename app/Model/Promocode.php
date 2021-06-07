<?php

namespace App\Model;
use Core\Utils\Answer;

class Promocode extends \Core\Base\Model {
    public function create($code, $percent){
        if($this->db->queryOne("SELECT id FROM promocode WHERE code = ?", [$code])){
            return Answer::error(["Данный код уже задействован"]);
        }

        if($this->db->execute("INSERT INTO promocode (code, percent) VALUES (?, ?)", [$code, $percent])){
            return Answer::success(["promocode_id" => $this->db->lastInsertId()]);
        }
        return Answer::error(["Неизвестная ошибка добавления промокода"]);
    }

    public function turn($promocode_id){

        if(!($query = $this->db->queryOne("SELECT id, active FROM promocode WHERE id = ?", [$promocode_id]))){
            return Answer::error(["Промокод с данным ID не найден"]);
        }

        $new_status = ($query['active'] == "y") ? "n" : "y";
        if($this->db->execute("UPDATE promocode SET active = '{$new_status}' WHERE id = ? LIMIT 1", [$promocode_id])){
            return Answer::success([]);
        }

        return Answer::error(["Неизвестная ошибка"]);
    }

    public function remove($promocode_id){
        if(!($this->db->execute("DELETE FROM promocode WHERE id = ?", [$promocode_id]))){
            return Answer::error(["Неизвестная ошибка удаления"]);
        }

        return Answer::success([]);
    }
}