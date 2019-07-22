<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 6/23/19
 * Time: 12:02 PM
 */

//----------------SUCCESS------------------------------
define("USER_CREATED", "Thank you for registration!");
define("SUCCESS", "Success!");
define("FOUND", "Found!");
define("SECRET_KEY", "MY_SECRET_KEY");
define("URL", "http://abstract.com");

//----------------ERROR------------------------------
define("EXIST", "Sorry the user with such username or email is already exist!");
define("ERROR_CREATE_USER", "Sorry some problems occurred while insertion to our db!");
define("ERROR_BOOKED", "Sorry but this data is already booked!");
define("ERROR_PASSED", "Data is in the past!");
define("ERROR_NO_DIFF", "Invalid time difference! Please make sure that you selected the right time!");
define("ERROR_UPDATE", "Error while updating!");
define("ERROR_EMPTY_JWT", "Sorry but JWT is empty!");
define("ERROR_INCORRECT_JWT", "Access denied! Incorrect JWT provided!");
define("ERROR", "Error!");
define("NO_USER", "Sorry, wrong username or email!");
define("ERROR_PASS", "Sorry, wrong password!");
define("ERROR_EMPTY", "Error empty fields!");
define("ERROR_EMAIL", "Please provide a valid email address!");
define("ERROR_DB", "Error DB!");
define("ERROR_WEEKEND", "Sorry but you can't book a room for weekend");
define("ERROR_NOID", "Error no user with such id!");
define("ERROR_CONNECT", "Connection error: ");
define("NOT_FOUND", "Not found!");
define("NO_MATCH", "No match! Did you just come up with that url by your own?");
define("PATTERN", "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i");