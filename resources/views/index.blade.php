<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List email System</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css"> <!-- load bootstrap via cdn -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css"> <!-- load fontawesome -->
    <style>
        body        { padding-top:30px; }
        form        { padding-bottom:20px; }
        .email    { padding-bottom:20px; }
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.min.js"></script> <!-- load angular -->

    <script src="js/controllers/main.js"></script>
    <script src="js/services/emailService.js"></script>
    <script src="js/app.js"></script>

</head>
<body class="container" ng-app="emailApp" ng-controller="mainController">
<div class="col-md-8 col-md-offset-2">
    <table class="email table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>E-mail</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="email in emails">
            <td>#@{{ email.id }}</td>
            <td>@{{email.email}}</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>