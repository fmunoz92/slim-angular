<!doctype html>
<html lang="en" 
      xmlns="http://www.w3.org/1999/xhtml" 
      xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Slim + Doctrine2 + Bootstrap + AngularJs</title>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <link href="/img/icons/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <meta property="fb:app_id" content="APP_ID">
  <meta property="og:title" content="">
  <meta property="og:description" content="">
  <meta property="og:type" content="website">
  <meta property="og:url" content="APP_URL">
  <meta property="og:image" content="APP_LOGO">
  <meta property="og:site_name" content="">
  <meta property="fb:admins" content="APP_ADMIN">

  <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="assets/lib/bootstrap/css/bootstrap-responsive.css">
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/views/articles.css">
  <!--[if lt IE 9]>
    <script src="assets/http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>

<header data-ng-include="'assets/views/header.html'" class="navbar navbar-fixed-top navbar-inverse">
</header>

<section class="content">
  <section class="container">
    <section data-ng-view="">
      <section data-ng-controller="IndexController" class="ng-scope">
        <h1>This is the home view</h1>
      </section>
    </section>  
  </section>
</section>


<script type="text/javascript" src="assets/lib/jquery/jquery.js"></script>

<!--Bootstrap-->
<script type="text/javascript" src="assets/lib/bootstrap/js/bootstrap.js"></script>

<!--AngularJS-->
<script type="text/javascript" src="assets/lib/angular/angular.js"></script>
<script type="text/javascript" src="assets/lib/angular-cookies/angular-cookies.js"></script>
<script type="text/javascript" src="assets/lib/angular-resource/angular-resource.js"></script>

<!--Angular UI-->
<script type="text/javascript" src="assets/lib/angular-bootstrap/ui-bootstrap-tpls.js"></script>
<script type="text/javascript" src="assets/lib/angular-ui-utils/modules/route/route.js"></script>

<!--Application Init-->
<script type="text/javascript" src="assets/js/app.js"></script>
<script type="text/javascript" src="assets/js/config.js"></script>
<script type="text/javascript" src="assets/js/directives.js"></script>
<script type="text/javascript" src="assets/js/filters.js"></script>

<!--Application Services-->
<script type="text/javascript" src="assets/js/services/global.js"></script>
<script type="text/javascript" src="assets/js/services/articles.js"></script>

<!--Application Controllers-->
<script type="text/javascript" src="assets/js/controllers/articles.js"></script>
<script type="text/javascript" src="assets/js/controllers/index.js"></script>
<script type="text/javascript" src="assets/js/controllers/header.js"></script>
<script type="text/javascript" src="assets/js/init.js"></script>

</body>
</html>