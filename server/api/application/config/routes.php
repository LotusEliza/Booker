<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/8/19
 * Time: 8:35 PM
 */

return [

    //----------------MAIN_CONTROLLER------------------------------
    '' => [
        'controller' => 'main',
        'action' => 'index',
    ],

    //----------------EVENTS_CONTROLLER------------------------------
    'event/events' => [
        'controller' => 'event',
        'action' => 'show',
    ],

    'event/events/([.][a-z]+)' => [
        'controller' => 'event',
        'action' => 'show',
    ],

    'event/create' => [
        'controller' => 'event',
        'action' => 'create',
    ],

    'event/create_rec' => [
        'controller' => 'event',
        'action' => 'createRec',
    ],

    'event/update' => [
        'controller' => 'event',
        'action' => 'update',
    ],

    'event/delete' => [
        'controller' => 'event',
        'action' => 'delete',
    ],

    //----------------ROOMS_CONTROLLER------------------------------
    'room/show' => [
        'controller' => 'room',
        'action' => 'show',
    ],

    'room/show/([.][a-z]+)' => [
        'controller' => 'room',
        'action' => 'show',
    ],

    //----------------USERS_CONTROLLER------------------------------
    'user/show' => [
        'controller' => 'user',
        'action' => 'show',
    ],

    'user/show/([.][a-z]+)' => [
        'controller' => 'user',
        'action' => 'show',
    ],

    'user/create' => [
        'controller' => 'user',
        'action' => 'createUser',
    ],

    'user/update' => [
        'controller' => 'user',
        'action' => 'updateUser',
    ],

    'user/delete' => [
        'controller' => 'user',
        'action' => 'delete',
    ],

    'user/login' => [
        'controller' => 'user',
        'action' => 'login',
    ],

];