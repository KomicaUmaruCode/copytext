<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
	
	<meta name="author" content="程式廚" />
	<meta name="dcterms.rightsHolder" content="程式廚" />
	<meta name="description" content="複製文產生器，一個讓你方便製作複製文的好所在。" />
	<meta name="robots" content="all" />
	<meta name="googlebot" content="all" />
	
	<meta property="og:title" content="複製文產生器"/>
	<meta property="og:type" content="website"/>
	<meta property="og:image" content="<?php echo base_url();?>assets/oglogo.jpg"/>
	<meta property="og:url" content="<?php echo base_url();?>"/>
	<meta property="og:description" content="複製文產生器，一個讓你方便製作複製文的好所在。"/>
	
	<title>複製文產生器 - 建立新複製文</title>
	<base href="<?php echo base_url();?>"/><!--[if IE]></base><![endif]-->
	
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/favicon.ico" >
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="assets/css/public.css" rel="stylesheet" type="text/css" />
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo base_url();?>">複製文產生器</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="<?php echo base_url();?>create">建立新複製文</a></li>
				<li><a href="<?php echo base_url();?>datalist">複製文列表</a></li>
				<li><a href="<?php echo base_url();?>about">關於</a></li>
			</ul>

			<!-- <form class="navbar-form navbar-right">
				<div class="form-group">
					<input type="text" placeholder="搜尋複製文" class="form-control">
				</div>
				<button type="button" class="btn btn-primary">搜尋</button>
			</form> -->
		</div><!--/.nav-collapse -->
	</div>
</nav>

<div class="container content">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<h2 style="text-align:center;">編輯原文</h2>
		    <textarea class="form-control" id="createorigintext" rows="10"></textarea>
		</div>
		<div class="col-md-6 col-xs-12">
		    <h2 style="text-align:center;">編輯複製文</h2>
		    <textarea class="form-control" id="createnewtext" rows="10" disabled="disabled"></textarea>
		    <div class="submitarea">
		    	<button type="button" class="btn btn-primary btn-lg" id="submitnewtext">送出</button>
				<button class="btn btn-default btn-lg" type="button"> # 符號數量 <span class="badge" id="starnumber">0</span></button>
				<div class="g-recaptcha" data-sitekey="6LcVkCkTAAAAAMIur6175z7PQnREB1H2PONEajQt"></div>
				<div class="alert alert-danger" role="alert" id="errormsg">不太對喔！</div>
		    </div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">注意事項</h3>
				</div>
				<div class="panel-body">
				1. 請使用英數半形 # 符號代表關鍵字。<br>
				2. # 符號關鍵字不得超過 20 個。<br>
				3. 複製文請至少超過 10 個字。<br>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view("toturial"); ?>
</div><!-- /.container -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="assets/js/public.js"></script>
</body>
</html>