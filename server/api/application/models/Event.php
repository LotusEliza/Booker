<?php

namespace application\models;
use application\core\Model;


class Event extends Model
{

    public function showEvents()
    {
        $result = $this->db->row('SELECT e.id, e.time_start, e.time_end, e.id_room, e.description, e.id_user,
                    e.create_time, u.username, e.id_parent  FROM events e  LEFT JOIN users u ON  e.id_user = u.id');
        return $result;
    }

    public function existEvent($time_start, $time_end, $id_room)
    {
        $result = $this->db->row("SELECT * FROM events WHERE (id_room = ".$id_room." AND
                    (('".$time_start."' <= time_start AND '".$time_start."' <= time_end AND
                        (('".$time_end."' >= time_start AND '".$time_end ."' >= time_end) OR ('".$time_end."' >=
                                time_start AND '".$time_end."' <= time_end))) OR
                    ('".$time_start."' >= time_start AND '".$time_start."' <= time_end AND
                        (('".$time_end."' >= time_start AND '".$time_end."' >= time_end) OR ('".$time_end."' >=
                                time_start AND '".$time_end."' <= time_end)))))");
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function existEventId($id_event, $id_user, $id_room)
    {
        $result = $this->db->row("SELECT * FROM events WHERE id_room = ".$id_room." AND id_user = ".$id_user."
                    AND id = ".$id_event." ");
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function getOneEvent($id_event)
    {
        $result = $this->db->row("SELECT * FROM events WHERE id = ".$id_event." ");
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function createEvent($array)
    {
        $result = $this->db->query('INSERT INTO events (id_user, id_room, description, time_start, time_end)
        VALUES (:id_user, :id_room, :description, :time_start, :time_end)', $array);
        $count = $result->rowCount();
        $id = $this->db->lastId();
        if($count){
            return $id;
        }else{
            return false;
        }
    }

    public function createRecurEvent($array)
    {
        $result = $this->db->query('INSERT INTO events (id_user, id_room, description, time_start, time_end)
        VALUES (:id_user, :id_room, :description, :time_start, :time_end)', $array);
        $count = $result->rowCount();
        $id = $this->db->lastId();
        $this->updateParId($id);
        if($count){
            return $id;
        }else{
            return false;
        }
    }

    public function updateParId($id)
    {
        $result = $this->db->query('UPDATE events SET id_parent='.$id.' WHERE id = '.$id.' ');
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

    public function createRecurEvents($array)
    {
        $result = $this->db->query('INSERT INTO events (id_user, id_room, description, time_start, time_end, id_parent)
        VALUES (:id_user, :id_room, :description, :time_start, :time_end, :id_parent)', $array);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

    public function updateEvent($array)
    {
        $result = $this->db->query('UPDATE events 
        SET id_user=:id_user, time_start = :time_start, time_end = :time_end,
        description = :description, id_room = :id_room 
        WHERE id = :id_event', $array);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

    public function updateRecurEvent($array)
    {
        $result = $this->db->query('UPDATE events SET 
        time_start = REPLACE(time_start, :old_time_start, :new_time_start),
        time_end = REPLACE(time_end, :old_time_end, :new_time_end), 
        description = :description, id_user = :id_user
        WHERE id_parent = :id_parent AND id >= :id_event', $array);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

    public function deleteEvent($array)
    {
        $result = $this->db->query('DELETE FROM events WHERE id=:id', $array);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

    public function deleteRecurEvent($array)
    {
        $result = $this->db->query('DELETE FROM events WHERE id_parent=:id_parent AND id >= :id', $array);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

}