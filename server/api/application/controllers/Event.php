<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/12/19
 * Time: 12:31 PM
 */

namespace application\controllers;

use application\core\Controller;
use DateTime;


class Event extends Controller
{

    public $id;
    public $duration;
    public $count;
    public $recurrent;

    /**
     * Get all events
     */
    public function showAction(){
        if ($this->jwtValid()) {
            $result = $this->model->showEvents();
            if ($result) {
                $var = ['events' => $result];
                $this->response(200, FOUND, $var, $this->format);
            } else {
                $this->response(404, NOT_FOUND, NULL, $this->format);
            }
        }else{
            $this->response(400,ERROR_EMPTY_JWT,NULL,$this->format);
        }
    }

    /**
     * Create new recurrent events, method - POST
     * @return | success OR error
     */
    public function createRecAction()
    {
        if ($this->jwtValid()) {
            $data = json_decode(file_get_contents("php://input"));
            $array =  (array) $data;
            foreach ($array as $key=>$val){
                $array[$key] = $this->testInput($val);
            }
            if(!$this->emptyVal($array)){
                if(!$this->passedDate($array['time_start'])){
                    $this->duration = $array['duration'];
                    $recur = $array['recur'];

                    unset($array['jwt'], $array['recur'], $array['duration']);
                    $dates = [$array];
                    $dateStart = explode (" ", $array['time_start']);
                    $dateEnd = explode (" ", $array['time_end']);
                    $date = $dateStart[0];
                    $timeStart = $dateStart[1];
                    $timeEnd = $dateEnd[1];

                    $dayNum = $this->dayOfWeek($dateStart[0]);
                    if($dayNum == 6 || $dayNum == 0){
                         $this->response(400, ERROR_WEEKEND, NULL, $this->format);
                    }else{

                        for($i=1; $i < $this->duration; $i++){
                            $dateStart = $this->incrTime($date, $recur);
                            $dayNum = $this->dayOfWeek($dateStart);
                            if($dayNum == 6 || $dayNum == 0){
                                $dateStart = $this->moveDate($date, $dayNum);
                                $array['time_start'] = $dateStart." ".$timeStart;
                                $array['time_end'] = $dateStart." ".$timeEnd;
                                $dates[] = $array;
                                $date = $dateStart;
                            }else{
                                $array['time_start'] = $dateStart." ".$timeStart;
                                $array['time_end'] = $dateStart." ".$timeEnd;
                                $dates[] = $array;
                                $date = $dateStart;
                            }
                        }
                        if($this->timeDiff($dates[0]['time_start'], $dates[0]['time_end'])){
                            if($this->checkFree($dates)){
                                foreach($dates as $index => $array) {
                                    if ($index == 0) {
                                        $this->id = $this->model->createRecurEvent($array);
                                        if (!$this->id) {
                                            $this->response(400, ERROR_DB, NULL, $this->format);
                                            break;
                                        } else {
                                            $this->count++;
                                        }
                                    } else {
                                        $array['id_parent'] = $this->id;
                                        $result = $this->model->createRecurEvents($array);
                                        if (!$result) {
                                            $this->response(400, ERROR_DB, NULL, $this->format);
                                        } else {
                                            $this->count++;
                                        }

                                        if($this->count == $this->duration){
                                            $this->response(200,SUCCESS,NULL,$this->format);
                                        }
                                    }
                                }
                            }else{
                                $this->response(409, ERROR_BOOKED, NULL, $this->format);
                            }
                        }else{
                            $this->response(400,ERROR_NO_DIFF,NULL,$this->format);
                        }
                    }
                }else{
                    $this->response(409,ERROR_PASSED,NULL,$this->format);
                }
            }else{
                $this->response(400,ERROR_EMPTY,NULL,$this->format);
            }
        }else{
         $this->response(400,ERROR_EMPTY_JWT,NULL,$this->format);
        }
    }

    /**
     * Create new event, method - POST
     * @return | success OR error
     */
    public function createAction()
    {
        if ($this->jwtValid()) {
            $data = json_decode(file_get_contents("php://input"));
            $array =  (array) $data;
            foreach ($array as $key=>$val){
                $array[$key] = $this->testInput($val);
            }
            unset($array['jwt']);
            if(!$this->emptyVal($array)){
                $dateStart = explode (" ", $array['time_start']);
                $dayNum = $this->dayOfWeek($dateStart[0]);
                if($dayNum == 6 || $dayNum == 0){
                $this->response(400, ERROR_WEEKEND, NULL, $this->format);
                }else{
                    if(!$this->passedDate($array['time_start'])){
                        if($this->existEvent($array['time_start'], $array['time_end'], $array['id_room'])){
                            $this->response(409,ERROR_BOOKED,NULL,$this->format);
                        }else{
                            if($this->timeDiff($array['time_start'], $array['time_end'])){
                                $result = $this->model->createEvent($array);
                                if($result){
                                    $this->response(200,SUCCESS,NULL,$this->format);
                                }else{
                                    $this->response(400,ERROR_DB,NULL,$this->format);
                                }
                            }else{
                                $this->response(400,ERROR_NO_DIFF,NULL,$this->format);
                            }
                        }
                    }else{
                        $this->response(409,ERROR_PASSED,NULL,$this->format);
                    }
                }
            }else{
                $this->response(400,ERROR_EMPTY,NULL,$this->format);
            }
        }else{
            $this->response(400,ERROR_EMPTY_JWT,NULL,$this->format);
        }
    }

    /**
     * Update event, method - POST
     * @return | string | success OR error
     */
    public function updateAction(){
        if ($this->jwtValid()) {
            $data = json_decode(file_get_contents("php://input"));
            $array =  (array) $data;
            foreach ($array as $key=>$val){
                $array[$key] = $this->testInput($val);
            }
            $this->recurrent = $array['recurrent'];
            unset($array['recurrent']);
            if(!$this->emptyVal($array)){
                if(!$this->passedDate($array['time_start'])){
                    if ($this->timeDiff($array['time_start'], $array['time_end'])) {

                        $result = $this->model->existEvent($array['time_start'], $array['time_end'], $array['id_room']);
                        if($result[0]['id_user'] == $array['id_user'] || !$result){

                            unset($array['jwt'], $array['id_role']);
                            if($this->recurrent == ''){
                                $result = $this->model->updateEvent($array);
                                if($result){
                                    $this->response(200,SUCCESS,NULL,$this->format);
                                }else{
                                    $this->response(400,ERROR_DB,NULL,$this->format);
                                }
                            }else{
                            $arrayRes = [];
                            $oldEvent = $this->model->existEventId($array['id_event'], $array['id_user'],$array['id_room']);
                            //new data
                            $dateStart = explode (" ", $array['time_start']);
                            $dateEnd = explode (" ", $array['time_end']);
                            $timeStart = $dateStart[1];
                            $timeEnd = $dateEnd[1];
                            //old data
                            $dateStartOld = explode (" ", $oldEvent[0]['time_start']);
                            $dateEndOld = explode (" ", $oldEvent[0]['time_end']);
                            $timeStartOld = $dateStartOld[1];
                            $timeEndOld = $dateEndOld[1];

                            $arrayRes['new_time_start'] = $timeStart;
                            $arrayRes['new_time_end'] = $timeEnd;
                            $arrayRes['old_time_start'] = $timeStartOld;
                            $arrayRes['old_time_end'] = $timeEndOld;
                            $arrayRes['description'] = $array['description'];
                            $arrayRes['id_user'] = $array['id_user'];
                            $arrayRes['id_event'] = $array['id_event'];
                            $arrayRes['id_parent'] = $this->recurrent;

                            $result = $this->model->updateRecurEvent($arrayRes);
                                if($result){
                                    $this->response(200,SUCCESS,NULL,$this->format);
                                }else{
                                    $this->response(400,ERROR_DB,NULL,$this->format);
                                }
                            }
                        }else{
                            $this->response(409,ERROR_BOOKED,NULL, $this->format);
                        }
                    }else{
                        $this->response(400,ERROR_NO_DIFF,NULL,$this->format);
                    }
                }else{
                    $this->response(409,ERROR_PASSED,NULL,$this->format);
                }
            }else{
                $this->response(400,ERROR_EMPTY,NULL,$this->format);
            }
        }else{
            $this->response(400,ERROR_EMPTY_JWT,NULL,$this->format);
        }
    }

    /**
     * Delete event, method - POST
     * @return | string | success OR error
     */
    public function deleteAction(){
        if ($this->jwtValid()) {
                $data = json_decode(file_get_contents("php://input"));
                $array =  (array) $data;
                foreach ($array as $key=>$val){
                    $array[$key] = $this->testInput($val);
                }
                $this->recurrent = $array['recurrent'];
                unset($array['jwt']);
                unset($array['recurrent']);
                if(!$this->emptyVal($array)){
                    $result = $this->model->getOneEvent($array['id']);
//                    echo $result[0]['time_start'];

                    if($result){
                        if(!$this->passedDate($result[0]['time_start'])){
                            if($this->recurrent){
                                $array['id_parent'] = $this->recurrent;
                                $result = $this->model->deleteRecurEvent($array);
                            }else{
                                $result = $this->model->deleteEvent($array);
                            }
                            if($result){
                                $this->response(200,SUCCESS,NULL,$this->format);
                            }else{
                                $this->response(400,ERROR_DB,NULL,$this->format);
                            }
                        }else{
                            $this->response(400,ERROR_PASSED,NULL,$this->format);
                        }
                    }else{
                        $this->response(400,NOT_FOUND,NULL,$this->format);
                    }
                }else{
                    $this->response(400,ERROR_EMPTY,NULL,$this->format);
                }
            }else{
             $this->response(400,ERROR_EMPTY_JWT,NULL,$this->format);
        }
    }

    /**
     * Overlap Check
     * @param $time_start, $time_end, $id_room | string
     * @return $result, false | array, bool
     */
    public function existEvent($time_start, $time_end, $id_room){
            $result = $this->model->existEvent($time_start, $time_end, $id_room);
            if($result){
                return $result;
            }else{
                return false;
            }
    }

    /**
     * Check if date for event is already passed
     * @param  $date | array
     * @return true, false | bool
     */
    public function passedDate($date){
        $date = new DateTime($date);
        $now = new DateTime();

        if($date < $now) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Check if $end_time > $start_time
     * @param  $start_time, $end_time| string
     * @return true OR false
     */
    public function timeDiff($start_time, $end_time){
        $minutes = (strtotime( $end_time ) - strtotime( $start_time ))/60;

        if($minutes <= 0){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Increment time for recurrent events
     * @param  $var, $incr| string
     * @return $date | string
     */
    public function incrTime($var, $incr){
        switch ($incr) {
            case 'weekly':
                $date = strtotime($var);
                $date = strtotime("+7 day", $date);
                $date =  date('Y-m-d', $date);
                return $date;
                break;
            case 'bi-weekly':
                $date = strtotime($var);
                $date = strtotime("+14 day", $date);
                $date =  date('Y-m-d', $date);
                return $date;
                break;
            case 'monthly':
                $date = strtotime($var);
                $date = strtotime("+1 month", $date);
                $date =  date('Y-m-d', $date);
                return $date;
                break;
        }

    }

    public function dayOfWeek ($dt){
        $datetime = date($dt);
        return $day_num = date('w', strtotime($datetime));
    }

    /**
     * Check if all recurrent events are free
     * @param  $dates | array
     * @return true false| bool
     */
    public function checkFree($dates)
    {
        $free = '';
        foreach ($dates as $index => $array) {
            if ($this->existEvent($array['time_start'], $array['time_end'], $array['id_room'])) {
                return false;
            } else {
                $free++;
                if($free == $this->duration){
                    return true;
                }
            }
        }
    }

    /**
     * Move date if recurrent event is on weekend
     * @param  $date, $dayNum | string, int
     * @return $date | string
     */
    public function moveDate($date, $dayNum){
        if($dayNum == 6){
            return date('Y-m-d', strtotime($date. ' + 2 days'));
        }elseif($dayNum == 0){
            return date('Y-m-d', strtotime($date. ' + 1 days'));
        }
    }
}