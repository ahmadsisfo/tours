<?php if (!defined('PATH'))  exit('No direct script access allowed'); ?>
<!DOCTYPE html> 
<html>
<head>
	<title>Fast Real Count</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="jquery-mobile/css/jquery.mobile-1.4.5.min.css" />
	
	<script src="jquery-mobile/js/jquery.min.js"></script>
	
	<script type="text/javascript" src="jquery-mobile/js/jquery.jqplot.js"></script>
	<script type="text/javascript" src="jquery-mobile/js/plugins/jqplot.barRenderer.js"></script>
	<script type="text/javascript" src="jquery-mobile/js/plugins/jqplot.pieRenderer.js"></script>
	<script type="text/javascript" src="jquery-mobile/js/plugins/jqplot.categoryAxisRenderer.js"></script>
	<script type="text/javascript" src="jquery-mobile/js/plugins/jqplot.pointLabels.js"></script>


	<script src="jquery-mobile/js/jquery.mobile-1.4.5.min.js"></script>
	<script src="jquery-mobile/js/jquery.flot.min.js"></script>
	<script src="jquery-mobile/js/jquery.flot.pie.min.js"></script>
	<script src="jquery-mobile/ajax/config/config.js"></script>
	<script src="jquery-mobile/ajax/control/index.js"></script>
	
	<link rel="stylesheet" type="text/css" hrf="jquery-mobile/js/jquery.jqplot.css" />
	<style type="text/css">
		div.graph {
			width: 100%;
			height: 400px;
		}
		.graph-2 {
			height: 170px;
			
		}
		.aside {
			position:absolute;
			z-index:99999;
			padding: .125em .625em;
			width: auto;
			min-height: 0;
			top: -15px;
			right: 0;
			bottom: auto;
			background: rgba(255,255,255,0.8);
			border-radius: 0 5px 0 10px;
			font-size:13px;
		}
	</style>
	
</head>

<body >
<!-- ======================================================================================================================================================================================================= -->

	<div data-role="page" id="index" >
		<div data-role="header" data-position="fixed" style="background:#46C637;" role="banner" class="ui-header ui-bar-inherit ui-header-fixed slidedown ui-panel-fixed-toolbar">
			<h1>Fast Real Count</h1>
			<a href="#index-left-panel" data-icon="bars" data-iconpos="notext" class="ui-link ui-btn-left ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Menu</a>
		</div>	
		<div data-role="panel" data-position-fixed="true" data-display="push" id="index-left-panel" class="ui-panel ui-panel-position-left ui-panel-display-push ui-body-a ui-panel-animate ui-panel-open">
			<ul data-role="listview" data-inset="true" data-split-theme="a" style="padding:6px;">
				<li>
					<a href="#login" data-rel="page" data-transition="slidefade"><img src="jquery-mobile/images/login.png">
					<h2 class="user-name">LOGIN</h2>
					<p class="user-name">sign in now</p>
					</a>
				</li>
			</ul>

			<ul data-role="listview" data-inset="true" data-divider-theme="a" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" style="padding:5px;">
				<li><a href="#" onclick="index.tab._sumbar();" data-rel="close" data-icon="home" data-role="button" data-corners="false" class="ui-btn-icon-left"> Dashboard</a></li>
				<li><a href="#statistik" onclick="statistik.tab._kabupaten();" data-rel="page" data-transition="slide" data-icon="bars" data-role="button" data-corners="false"> Barchart</a></li>
				<li><a href="#setting" data-rel="page" data-transition="slide" data-icon="gear" data-role="button" data-corners="false"> Setting</a></li>
				<li><a href="#about" data-rel="page" data-transition="slide" data-icon="info" data-role="button" data-corners="false"> About</a></li>
			</ul>					
        </div>
		<div role="main" class="ui-content">		
			<div class="ui-bar ui-bar-a" style="padding:0 6px 0; background:#46C637;">
				<div id="index-tab-control" >
					<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
						<a class="ui-shadow ui-btn ui-corner-all" href="#">Sumatera Barat</a>
					</fieldset>
				</div>
			</div>
			<div id="index-sumbar">
				<a href="#" onclick="index.tab._kabupaten();" class="ui-shadow ui-btn ui-corner-all" data-ajax="false"><div id="index-graph1" class="graph"><br/><br/><br/><br/><br/>Loading...<br/><img src="jquery-mobile/images/20.gif" /></div></a>
			</div>
			<div id="index-kabupaten" class="ui-grid-a"></div>
			<div id="index-kecamatan" class="ui-grid-a"></div>
			<div id="index-kelurahan" class="ui-grid-a"></div>
			<div id="index-tps" class="ui-grid-b"></div>
			<div class="ui-corner-all" id="index-tps-popup-input" data-role="popup" data-overlay-theme="b" data-theme="a"><a class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" href="#" data-rel="back">Close</a>
				<a class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right" href="#" data-rel="back">Close</a>
				<div style="padding:10px 20px 15px;">
				<h2>Keterangan TPS</h2>
				<table>
					<tr><td>NO TPS</td><td>&nbsp : &nbsp </td><td id="index-tps-no"></td></tr>
					<tr><td>Kelurahan</td><td>&nbsp : &nbsp </td><td id="index-tps-kelurahan"></td></tr>
					<tr><td>Pemilih Tetap L</td><td>&nbsp : &nbsp </td><td id="index-tps-pt_l"></td></tr>
					<tr><td>Pemilih Tetap P</td><td>&nbsp : &nbsp </td><td id="index-tps-pt_p"></td></tr>
					<tr><td>Pemilih Tetap P+L</td><td>&nbsp : &nbsp </td><td id="index-tps-pt"></td></tr>
					<tr><td>Suara Sah</td><td>&nbsp : &nbsp </td><td id="index-tps-sah"></td></tr>
					<tr><td>Suara Tidak Sah</td><td>&nbsp : &nbsp </td><td id="index-tps-tdk_sah"></td></tr>
					<tr><td>Surat Suara yang terpakai</td><td>&nbsp : &nbsp </td><td id="index-tps-digunakan"></td></tr>
				</table>
				
				</div>
			</div>
		</div>
		
		<div data-role="footer" style="background:#46C637" data-position="fixed" class="ui-footer ui-bar-inherit ui-footer-fixed slideup ui-panel-fixed-toolbar">
			<h4>&copy; 2015 "Real Count"</h4>
		</div>
		
		<div data-role="popup" data-overlay-theme="b" id="LogoutDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;" >
			<div data-role="header" data-theme="a" role="banner" class="ui-header ui-bar-a">
			<h1 class="ui-title" role="heading" aria-level="1">Log Out Confirm...</h1>
			</div>
			<div role="main" class="ui-content">
				<h3 class="ui-title">Are you sure you want to log out from this page?</h3>
			
				<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Cancel</a>
				<a href="./?p=logout" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-transition="flow">Log Out</a>
			</div>
		</div>
	</div>
	
<!-- ======================================================================================================================================================================================================= -->

	<div data-role="page" id="statistik" >
		<div data-role="header" data-position="fixed" style="background:#46C637;" role="banner" class="ui-header ui-bar-inherit ui-header-fixed slidedown ui-panel-fixed-toolbar">
			<h1>Fast Real Count</h1>
			<a href="#statistik-left-panel" data-icon="bars" data-iconpos="notext" class="ui-link ui-btn-left ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Menu</a>
			<a href="#index" data-position-to="window" data-iconpos="notext" data-rel="page" data-transition="flow" data-icon="home"  aria-haspopup="true" aria-owns="popupDialog" aria-expanded="false">logout</a>			
		</div>	
		<div data-role="panel" data-position-fixed="true" data-display="push" id="statistik-left-panel" class="ui-panel ui-panel-position-left ui-panel-display-push ui-body-a ui-panel-animate ui-panel-open">
			<ul data-role="listview" data-inset="true" data-split-theme="a" style="padding:6px;">
				<li>
					<a href="#login" data-rel="page" data-transition="slidefade"><img src="jquery-mobile/images/login.png">
					<h2 class="user-name">LOGIN</h2>
					<p class="user-name">sign in now</p>
					</a>
				</li>
			</ul>

			<ul data-role="listview" data-inset="true" data-divider-theme="a" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" style="padding:5px;">
				<li><a href="#index" data-rel="page"  data-transition="flow" data-icon="home" data-role="button" data-corners="false" class="ui-btn-icon-left"> Dashboard</a></li>
				<li><a href="#statistik" onclick="statistik.tab._kabupaten();" data-rel="close" data-transition="slide" data-icon="bars" data-role="button" data-corners="false"> Barchart</a></li>
				<li><a href="#setting" data-rel="page" data-transition="slide" data-icon="gear" data-role="button" data-corners="false"> Setting</a></li>
				<li><a href="#about" data-rel="page" data-transition="slide" data-icon="info" data-role="button" data-corners="false"> About</a></li>
			</ul>					
        </div>
		<div role="main" class="ui-content">		
			<div class="ui-bar ui-bar-a" style="padding:0 6px 0; background:#46C637;">
				<div id="statistik-tab-control" >
					<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
						<a class="ui-shadow ui-btn ui-corner-all" href="#">Sumatera Barat</a>
					</fieldset>
				</div>
			</div>
			<div id="statistik-sumbar">
				<a href="#" onclick="statistik.tab._kabupaten();" class="ui-shadow ui-btn ui-corner-all" data-ajax="false"><div id="statistik-graph1" class="graph"><br/><br/><br/><br/><br/>Loading...<br/><img src="jquery-mobile/images/20.gif" /></div></a>
			</div>
			<div id="statistik-kabupaten" style="min-height:300px"></div>
			<div id="statistik-kecamatan"></div>
			<div id="statistik-kelurahan"></div>
			<div id="statistik-tps"></div>
			
		</div>
		
		<div data-role="footer" style="background:#46C637" data-position="fixed" class="ui-footer ui-bar-inherit ui-footer-fixed slideup ui-panel-fixed-toolbar">
			<h4>&copy; 2015 "Real Count"</h4>
		</div>
		
		<div data-role="popup" data-overlay-theme="b" id="LogoutDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;" >
			<div data-role="header" data-theme="a" role="banner" class="ui-header ui-bar-a">
			<h1 class="ui-title" role="heading" aria-level="1">Log Out Confirm...</h1>
			</div>
			<div role="main" class="ui-content">
				<h3 class="ui-title">Are you sure you want to log out from this page?</h3>
			
				<a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Cancel</a>
				<a href="./?p=logout" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-transition="flow">Log Out</a>
			</div>
		</div>
	</div>
	
<!-- ======================================================================================================================================================================================================= -->
	<div data-role="page" id="login" >
		<div data-role="header" data-position="fixed" style="background:#46C637" role="banner" class="ui-header ui-bar-inherit ui-header-fixed slidedown ui-panel-fixed-toolbar">
			<h1>Login</h1>
			<a href="#login-left-panel" data-icon="bars" data-iconpos="notext" class="ui-link ui-btn-left ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Menu</a>
			<a href="#index" data-position-to="window" data-iconpos="notext" data-rel="page" data-transition="flow" data-icon="home"  aria-haspopup="true" aria-owns="popupDialog" aria-expanded="false">logout</a>		</div>	
		<div data-role="panel" data-position-fixed="true" data-display="push" id="login-left-panel" class="ui-panel ui-panel-position-left ui-panel-display-push ui-body-a ui-panel-animate ui-panel-open">
			<ul data-role="listview" data-inset="true" data-split-theme="a" style="padding:6px;">
				<li>
					<a href="#login" data-rel="close" data-transition="slide"><img src="jquery-mobile/images/login.png">
					<h2 class="user-name">LOGIN</h2>
					<p class="user-name">sign in now</p>
					</a>
				</li>
			</ul>

			<ul data-role="listview" data-inset="true" data-divider-theme="a" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" style="padding:5px;">
				<li><a href="#index" onclick="index.tab._sumbar();" data-rel="page" data-transition="flow" data-icon="home" data-role="button" data-corners="false" class="ui-btn-icon-left"> Dashboard</a></li>
				<li><a href="#statistik" onclick="statistik.tab._kabupaten();" onclick="statistik.tab._kabupaten();" data-rel="page" data-transition="slide" data-icon="bars" data-role="button" data-corners="false"> Barchart</a></li>
				<li><a href="#setting" data-rel="page" data-transition="slide" data-icon="gear" data-role="button" data-corners="false"> Setting</a></li>
				<li><a href="#about" data-rel="page" data-transition="slide" data-icon="info" data-role="button" data-corners="false"> About</a></li>
			</ul>					
        </div>

		<div role="main" class="ui-content">
			<div data-demo-html="true" data-demo-css="#combined-heading-and-section">
				<div class="ui-corner-all custom-corners">
					<p id="login-form-error">
						
					</p>
					<p>
					<form id="login-form-input" onsubmit="login.form._input(); return false;" class="validatedForm" >
						<input type="hidden" name="signin" />
						<div data-demo-html="true">
							<label for="textinput-2">Username Input :</label>
							<input type="text" name="username" id="focus" placeholder="Username" value=""  required>
						</div><!--/demo-html -->
						<div data-demo-html="true">
							<label for="textinput-2">Password Input :</label>
							<input type="password" name="password"" placeholder="Password" value="" required>
						</div><!--/demo-html -->
						<div data-demo-html="true">
							<label for="submit-2">Send:</label>
							<button class="ui-shadow ui-btn ui-corner-all" style="background:#46C637" type="submit" id="submit-1">Sign In</button>
						</div><!--/demo-html -->
					</form>
					</p>
				</div>
			</div>
		</div>
		
		<div data-role="footer" style="background:#46C637" data-position="fixed" class="ui-footer ui-bar-inherit ui-footer-fixed slideup ui-panel-fixed-toolbar">
			<h4>&copy; 2015 "Real Count"</h4>
		</div>
	</div>

<!-- ======================================================================================================================================================================================================= -->

	<div data-role="page" id="setting" >
		<div data-role="header" data-position="fixed" style="background:#46C637" role="banner" class="ui-header ui-bar-inherit ui-header-fixed slidedown ui-panel-fixed-toolbar">
			<h1>Setting</h1>
			<a href="#setting-left-panel" data-icon="bars" data-iconpos="notext" class="ui-link ui-btn-left ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Menu</a>
			<a href="#index" data-position-to="window" data-iconpos="notext" data-rel="page" data-transition="flow" data-icon="home"  aria-haspopup="true" aria-owns="popupDialog" aria-expanded="false">logout</a>		
		</div>	
		<div data-role="panel" data-position-fixed="true" data-display="push" id="setting-left-panel" class="ui-panel ui-panel-position-left ui-panel-display-push ui-body-a ui-panel-animate ui-panel-open">
			<ul data-role="listview" data-inset="true" data-split-theme="a" style="padding:6px;">
				<li>
					<a href="#login" data-rel="close" data-transition="slide"><img src="jquery-mobile/images/login.png">
					<h2 class="user-name">LOGIN</h2>
					<p class="user-name">sign in now</p>
					</a>
				</li>
			</ul>
			<ul data-role="listview" data-inset="true" data-divider-theme="a" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" style="padding:5px;">
				<li><a href="#index" onclick="index.tab._sumbar();" data-rel="page" data-transition="flow" data-icon="home" data-role="button" data-corners="false" class="ui-btn-icon-left"> Dashboard</a></li>
				<li><a href="#statistik" onclick="statistik.tab._kabupaten();" data-rel="page" data-transition="slide" data-icon="bars" data-role="button" data-corners="false"> Barchart</a></li>
				<li><a href="#setting" data-rel="close" data-transition="none" data-icon="gear" data-role="button" data-corners="false"> Setting</a></li>
				<li><a href="#about" data-rel="page" data-transition="slide" data-icon="info" data-role="button" data-corners="false"> About</a></li>
			</ul>					
        </div>

		<div role="main" class="ui-content">
			<div data-demo-html="true" data-demo-css="#combined-heading-and-section">
				<div class="ui-corner-all custom-corners">
					<button class="ui-shadow ui-btn ui-corner-all" onclick="setting.url._default(); return false;">URL Default</button>
					<p>
					<form id="login-form-input" onsubmit="setting.url._change(); return false;" method="post" class="validatedForm" >
						<div data-demo-html="true">
							<label for="textinput-2">Ganti Server URL</label>
							<input type="text" name="new-url" placeholder="exp : rc-sumbar.com" value=""  required>
						</div><!--/demo-html -->
						<div data-demo-html="true">
						
							<button class="ui-shadow ui-btn ui-corner-all" style="background:#46C637" name="signin" type="submit" id="submit-1">Submit</button>
							
						</div><!--/demo-html -->
					</form>
					</p>
				</div>
			</div>
		</div>
		
		<div data-role="footer" style="background:#46C637" data-position="fixed" class="ui-footer ui-bar-inherit ui-footer-fixed slideup ui-panel-fixed-toolbar">
			<h4>&copy; 2015 "Real Count"</h4>
		</div>
	</div>
<!-- ======================================================================================================================================================================================================= -->

	<div data-role="page" id="about" >
		<div data-role="header" data-position="fixed" style="background:#46C637" role="banner" class="ui-header ui-bar-inherit ui-header-fixed slidedown ui-panel-fixed-toolbar">
			<h1>About</h1>
			<a href="#about-left-panel" data-icon="bars" data-iconpos="notext" class="ui-link ui-btn-left ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button" role="button">Menu</a>
			<a href="#index" data-position-to="window" data-iconpos="notext" data-rel="page" data-transition="flow" data-icon="home"  aria-haspopup="true" aria-owns="popupDialog" aria-expanded="false">logout</a>		
		</div>	
		<div data-role="panel" data-position-fixed="true" data-display="push" id="about-left-panel" class="ui-panel ui-panel-position-left ui-panel-display-push ui-body-a ui-panel-animate ui-panel-open">
			<ul data-role="listview" data-inset="true" data-split-theme="a" style="padding:6px;">
				<li>
					<a href="#login" data-rel="close" data-transition="slide"><img src="jquery-mobile/images/login.png">
					<h2 class="user-name">LOGIN</h2>
					<p class="user-name">sign in now</p>
					</a>
				</li>
			</ul>

			<ul data-role="listview" data-inset="true" data-divider-theme="a" class="ui-listview ui-listview-inset ui-corner-all ui-shadow" style="padding:5px;">
				<li><a href="#index" onclick="index.tab._sumbar();" data-rel="page" data-transition="flow" data-icon="home" data-role="button" data-corners="false" class="ui-btn-icon-left"> Dashboard</a></li>
				<!--<li><a href="#statistik" onclick="statistik.tab._kabupaten();" data-rel="page" data-transition="slide" data-icon="bars" data-role="button" data-corners="false"> Statistik</a></li>-->
				<li><a href="#setting" data-rel="page" data-transition="slide" data-icon="gear" data-role="button" data-corners="false"> Setting</a></li>
				<li><a href="#about" data-rel="close" data-transition="none" data-icon="info" data-role="button" data-corners="false"> About</a></li>
			</ul>					
        </div>

		<div role="main" class="ui-content">
			<div data-demo-html="true" data-demo-css="#combined-heading-and-section">
				<div class="ui-corner-all custom-corners">
					
					<div class="ui-body ui-body-a" >
						<p>
							Selamat Datang di Aplikasi Real Count PILKADA SUMBAR 2015<br/>
							versi 1.0.0 RnF
						</p>
					</div>
				</div>
			</div>
		</div>
		
		<div data-role="footer" style="background:#46C637" data-position="fixed" class="ui-footer ui-bar-inherit ui-footer-fixed slideup ui-panel-fixed-toolbar">
			<h4>&copy; 2015 "Real Count"</h4>
		</div>
	</div>
</body>
	
</html>
		
		
   