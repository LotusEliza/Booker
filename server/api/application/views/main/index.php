<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <title>My RESTful API</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- stylesheets-->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic" rel="stylesheet">
    
    <!-- -----------------------Home links------------------------- -->
<!--    /var/www/html/my_booker/server/api/public/styles/font-awesome/css/font-awesome.min.css-->
    <!-- <link href="/my_booker/server/api/public/styles/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/my_booker/server/api/public/css/main.css" rel="stylesheet"> -->
 
 <!-- -----------------------Class links------------------------- -->
   <link href="/~user3/REST_API/server/api/public/styles/font-awesome/css/font-awesome.min.css" rel="stylesheet">
   <link href="/~user3/REST_API/server/api/public/css/main.css" rel="stylesheet">
    
</head>
<body>
<header class="header navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle">
                    <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar">
                    </span></button><a href="#" class="navbar-brand">REST API</a>
            </div>
            <div id="navbar-collapse-1" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#home">Introduction</a></li>
                    <li><a href="#routes">Routes</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="main-content">
    <div class="banner">
        <div class="container" style="padding-bottom:40px;">
            <h2>RESTful API for Online Booking System.</h2>
            <p style="font-size:16px;">The API is written in PHP with MySQL as the database (PDO connection)</p>
        </div>
    </div>

    <div id="routes" class="features">
        <header>
            <h3 class="text-center">Routes</h3>
            <h4 class="text-center">Following are the routes defined for the api and their description</h4>
        </header>
        <div class="container">
            <div class="row">
                <div class="feature-item col-md-12">
                    <h3>USER</h3>
                    <div>
                        <p>&#9755 /api/user/create/ [json]: Register yourself. The routes can not be accessed
                            until a user has registered and loggdin.</p>
                        <div id="example">
                            Example:
                            <pre>
                            {
                                "id_role": "1",
                                "password": "12345",
                                "username": "user",
                                "email": "user@gmail.com"
                             }
                            </pre>
                        </div>
                        <p>&#9755 /api/user/login [json]: Login. The routes can not be accessed
                            until a user has loggedin. You will get a response that provides user credentials and JWT
                            that you need to save and send to us with every next request.</p>
                        <div id="example">
                            Example:
                            <pre>
                            {
                                "password": "12345",
                                "username": "YourUserName",
                                "email": "user@gmail.com"
                            }
                            </pre>
                        </div>
                        <div id="example">
                            You will get a response with JWT:
                            <pre>
                            {
                                "jwt" : "YourUnicJWT"
                            }
                            </pre>
                        </div>
                        <p>&#9755 /api/user/delete/ [json]: Delete the user (only admin).</p>
                        <div id="example">
                            Example:
                            <pre>
                            {
                                "jwt" : "YourUnicJWT"
                                "id": "1",
                             }
                            </pre>
                        </div>
                        <p>&#9755 /api/user/update/ [json]: Update the user (only admin).</p>
                        <div id="example">
                            Example:
                            <pre>
                            {
                                "jwt" : "YourUnicJWT"
                                "id": "1",
                                "username": "UserName",
                                "email": "user@gmail.com"
                             }
                            </pre>
                        </div>
                        <p>&#9755 To Logout from the session you should destroy a JWT that we will provide you or it
                            will be automatically expired in some time.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="feature-item col-md-12 ">
                    <h3>EVENT</h3>
                    <div>
                        <p>&#9755 /api/event/events/ [json]: Returns all events.</p>
                        <div id="example">
                            Example:
                            <pre>
                            {
                                "jwt" : "YourUnicJWT",
                            }
                            </pre>
                        </div>
                        <p>&#9755 /api/event/create/ [json]: Create single event.</p>
                        <div id="example">
                            Example:
                            <pre>
                            {
                                "jwt" : "YourUnicJWT"
                                "id_user": "1",
                                "id_room": "1",
                                "description": "The first event",
                                "time_start": "2032-12-02 18:00:00",
                                "time_end": "2032-12-02 19:00:00",
                            }
                            </pre>
                        </div>
                        <p>&#9755 /api/event/create_rec/ [json]: Create recurrent events ("recur": weekly,
                            bi-weekly, monthly, duration: max 4).</p>
                        <div id="example">
                            Example:
                            <pre>
                           {
                                "jwt" : "YourUnicJWT"
                                "id_user": "1",
                                "id_room": "1",
                                "description": "The first event",
                                "time_start": "2032-12-02 18:00:00",
                                "time_end": "2032-12-02 19:00:00",
                                "recur": "weekly",
                                "duration" : 3
                            }
                            </pre>
                        </div>
                        <p>&#9755 /api/event/update/ [json]: Update existing event if it's not in the past (empty
                            "recurrent" if you want to update
                            only one event).</p>
                        <div id="example">
                            Example:
                            <pre>
                           {
                                "jwt" : "YourUnicJWT",
                                "id_event": "1",
                                "id_user": "1",
                                "id_room": "1",
                                "description": "Text",
                                "time_start": "2032-12-02 18:00:00",
                                "time_end": "2032-12-02 19:00:00",
                                "recurrent": "81"
                           }
                            </pre>
                        </div>
                        <p>&#9755 /api/event/delete/ [json]: Delete existing event. User can delete only events that
                            belong to him and Admin can delete all events (empty "recurrent" if you want to delete
                            only one event).</p>
                        <div id="example">
                            Example:
                            <pre>
                             {
                                "jwt" : "YourUnicJWT",
                                "id": "78",
                                "recurrent": "78"
                             }
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="feature-item col-md-12 ">
                    <h3>ROOM</h3>
                    <div>
                        <p>&#9755 /api/room/show/ [json]: Get the list of all available rooms.</p>
                        <div id="example">
                            Example:
                            <pre>
                           {
                                "jwt" : "YourUnicJWT",
                           }
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<footer class="footer text-center"> © Copyright 2019</footer>

<!-- Scripts-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/server/api/public/scripts/jquery.min.js"><\/script>')</script>

<!-- -----------------------Home links------------------------- -->
<!-- <script src="/my_booker/server/api/public/scripts/bootstrap.min.js"></script>
<script src="/my_booker/server/api/public/scripts/jquery.smooth-scroll.min.js"></script>
<script src="/my_booker/server/api/public/scripts/main.js"></script> -->

<!-- -----------------------Class links------------------------- -->
<script src="/~user3/REST_API/server/api/public/scripts/bootstrap.min.js"></script>
<script src="/~user3/REST_API/server/api/public/scripts/jquery.smooth-scroll.min.js"></script>
<script src="/~user3/REST_API/server/api/public/scripts/main.js"></script>
</body>
</html>