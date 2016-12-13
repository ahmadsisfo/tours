$(window).bind("orientationchange", function(){
    var orientation = window.orientation;
    var new_orientation = (orientation) ? 0 : 180 + orientation;
    $('body').css({
        "-webkit-transform": "rotate(" + new_orientation + "deg)"
    });
});
UChost = window.localStorage.getItem("rcuserURL");
$( document ).ready(function() {
	if(window.localStorage.getItem("rclogIN") == 1) {
		$('.user-name').html(window.localStorage.getItem("rcuserNAME"));
		$('.ket-admin').html('Admin '+window.localStorage.getItem("rcuserLEVEL")+'<br/>'+window.localStorage.getItem("rcuserNAMA_WIL"));
		alert("Selamat datang "+window.localStorage.getItem("rcuserNAME")+"\nAnda login sebagai admin "+window.localStorage.getItem("rcuserLEVEL")+" Kecamatan "+window.localStorage.getItem("rcuserNAMA_WIL"));
	}
	UChost = window.localStorage.getItem("rcuserURL");
});

//======================================================================JAVASCRIPT===============================================================================================================================
function panelswip(halaman){
	$( document ).on( "swipeleft swiperight", "#"+halaman, function( e ) {
		if ( $.mobile.activePage.jqmData( "panel" ) !== "open" ) {
            if ( e.type === "swipeleft"  ) {
                $( "#"+halaman+"-right-panel" ).panel( "open" );
            } else if ( e.type === "swiperight" ) {
                $( "#"+halaman+"-left-panel" ).panel( "open" );
            }
        }
    });
}

$(document).on('pageinit', '#rekap', function() {
	panelswip('rekap');
	rekap.select.kabupaten();
	//login.form.checkaktivasi();
});

$(document).on('pageinit', '#about', function() {
	panelswip('about');
	//login.form.checkaktivasi();
});
$(document).on('pageinit', '#tpsPage', function() {
    tpsPage.tab._kabupaten();
	panelswip('tpsPage');
	//login.form.checkaktivasi();
});
$(document).on('pageinit', '#report', function() {
	//report.kesalahan._read();
	panelswip('report');
	report.tps._init();
	report.kesalahan._error();
	report.kesalahan._warning();
	login.form.checkaktivasi();
	
});
$(document).on('pageinit', '#index', function() {
	index.tab._sumbar();
	panelswip('index');
	//login.form.checkaktivasi();
	
});
$(document).on('pageinit', '#hitung', function() {
    hitung.tab._kabupaten();
	//panelswip('hitung');
	login.form.checkaktivasi();
});
$(document).on('pageinit', '#admin', function() {
	admin.tab._korsad();
	panelswip('admin');
	login.form.checkaktivasi();
});
$(document).on('pageinit', '#setting', function() {
	panelswip('setting');
	setting.profil._readUpdate(window.localStorage.getItem("rcuserID"));
	login.form.checkaktivasi();
});

//===============================================================================================================================================================================================================	

var tpsPage = {

	server : '_control/admin/tps.php?ajax&',
	//server : 'http //localhost/myjob/ipna/_control/admin/tps.php?ajax&',
	kabupatenS	: {},
	kecamatanS	: {},
	kelurahanS	: {},
		
	tab : {
			
		_kabupaten : function(){
			tpsPage.tab._closeall();
			tpsPage.kabupaten._init();
			$('#tpsPage-kabupaten').show();
		},
		
		_kecamatan : function(id_kabupaten){
			tpsPage.tab._closeall();
			tpsPage.kecamatan._init(id_kabupaten);
			$('#tpsPage-kecamatan').show();
		},
		
		_kelurahan : function(id_kecamatan){
			tpsPage.tab._closeall();
			tpsPage.kelurahan._init(id_kecamatan);
			$('#tpsPage-kelurahan').show();
		},
		
		_tps : function(id_kelurahan){
			tpsPage.tab._closeall();
			tpsPage.tps._init(id_kelurahan);
			$('#tpsPage-tps').show();
		},
		
		_closeall : function(){
			$('#tpsPage-kabupaten').hide();
			$('#tpsPage-kecamatan').hide();
			$('#tpsPage-kelurahan').hide();
			$('#tpsPage-tps').hide();
		},
	},
	
	tps : {
		_init : function(id_kelurahan){
			tpsPage.tps._tab(id_kelurahan);
			tpsPage.tps._read(id_kelurahan);
		},

		_tab : function(id_kelurahan) {
			$('#tpsPage-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="tpsPage.tab._kabupaten()" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="tpsPage.tab._kecamatan('+tpsPage.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+tpsPage.kabupatenS.nama+'</a>'+
					'<a href="#" onclick="tpsPage.tab._kelurahan('+tpsPage.kecamatanS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+tpsPage.kecamatanS.nama+'</a>'+
					'<select id="tpsPage-kelurahan-select" onchange="tpsPage.tab._tps(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#tpsPage-tab-control').trigger('create');
			tpsPage.kelurahan._select(id_kelurahan);
		},
		
		_delete : function(id) {
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=tps_delete&id='+id,
				success: function (result) {
					if(result.status) {
						tpsPage.tps._read(tpsPage.kelurahanS.id);
					}
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});
		}, 
		
		
		_update : function() {
    
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=tps_update',
				data: $('#tpsPage-tps-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						$('#tpsPage-tps-popup-input').popup( 'close' );
						$('#tpsPage-tps-form-input')[0].reset();
						tpsPage.tps._read(tpsPage.kelurahanS.id);
					}
				},
			});                   
        },
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=tps_readUpdate&id='+id,
				success: function (result) {
						var baruHtml = '';
						for (var i in result){
							for (var j in result[i]){
								tpsPage.tps._pFU();
								$('#tpsPage-tps-form-input input[name="id"]').val(result[i][j].id);
								$('#tpsPage-tps-form-input input[name="no"]').val(result[i][j].no);
								$('#tpsPage-tps-form-input input[name="alamat"]').val(result[i][j].alamat);
								$('#tpsPage-tps-form-input input[name="pt_l"]').val(result[i][j].pt_l);
								$('#tpsPage-tps-form-input input[name="pt_p"]').val(result[i][j].pt_p);
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
					
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
			
		},
		
		_read : function(id_kelurahan) {
			$('#tpsPage-tps-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=tps_read&id_kelurahan='+id_kelurahan,
				success: function (result) {
					if(result.status==false){
						$('#tpsPage-tps-table tbody').html(baruHtml);
						$('#tpsPage-tps-table').table( 'rebuild' );
					} else {
						//alert(JSON.stringify(result)); 
						var no=1;
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<tr>'+
										'<th>'+result[i][j].no+'</th>'+
										'<td>'+result[i][j].pt_l+'</td>'+
										'<td>'+result[i][j].pt_p+'</td>'+
										'<td>'+(parseFloat(result[i][j].pt_l)+parseFloat(result[i][j].pt_p))+'</td>'+
									'</tr>';no++;
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('#tpsPage-tps-table tbody').html(baruHtml);
						$('#tpsPage-tps-table').table( 'rebuild' );
						
					}
				}
			});                 
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=tps_input&id_kelurahan='+tpsPage.kelurahanS.id,
				data: $('#tpsPage-tps-form-input').serialize(),
				success: function (result) {
					if(result.status) {	
						$('#tpsPage-tps-popup-input').popup( 'close' );
						$('#tpsPage-tps-form-input')[0].reset();
						tpsPage.tps._read(tpsPage.kelurahanS.id);
					} 
				}
			});
		},
		
		_pFI : function(){
			$('#tpsPage-tps-popup-input h3').html('Input Data tps');
			$('#tpsPage-tps-form-input')[0].reset();
			$('#tpsPage-tps-form-input').attr('onsubmit','tpsPage.tps._input(); return false;');
		}, 
		
		_pFU : function(id){
			$('#tpsPage-tps-popup-input h3').html('Edit Data tps');
			$('#tpsPage-tps-form-input').attr('onsubmit','tpsPage.tps._update(); return false;');
		},
	
		_pBD : function(id){
			$('#tpsPage-popup-delete').html('<a onclick="tpsPage.tps._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		}
	},
	
	kelurahan : {
		_init : function(id_kecamatan){
			tpsPage.kelurahan._tab(id_kecamatan);
			tpsPage.kelurahan._read(id_kecamatan);
		},

		_tab : function(id_kecamatan) {
			$('#tpsPage-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="tpsPage.tab._kabupaten()" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="tpsPage.tab._kecamatan('+tpsPage.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+tpsPage.kabupatenS.nama+'</a>'+
					'<select id="tpsPage-kecamatan-select" onchange="tpsPage.tab._kelurahan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#tpsPage-tab-control').trigger('create');
			tpsPage.kecamatan._select(id_kecamatan);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kelurahan_read&id_kecamatan='+tpsPage.kecamatanS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kelurahan -</option>'; 
					for (var i in result){
						if(result[i]['Kelurahan'].id == selected){
							tpsPage.kelurahanS = {'id':result[i]['Kelurahan'].id,'nama':result[i]['Kelurahan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kelurahan'].id+'">'+result[i]['Kelurahan'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kelurahan'].nama));
					}
					$('#tpsPage-kelurahan-select').html(baruHtml);
					$('#tpsPage-kelurahan-select').val(selected).selectmenu('refresh');
				}
			});               
		},
		
		_read : function(id_kecamatan) {
			$('#tpsPage-kelurahan-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kelurahan_read&id_kecamatan='+id_kecamatan,
				success: function (result) {
					if(result.status==false){
						$('#tpsPage-kelurahan-table tbody').html(baruHtml);
						$('#tpsPage-kelurahan-table').table( 'rebuild' );
					} else {
						//alert(JSON.stringify(result)); 
						var no=1;
						for (var i in result){
							baruHtml += '<tr>'+
									'<th>'+no+'</th>'+
									'<td><a href="#" onclick="tpsPage.tab._tps(\''+result[i]['Kelurahan'].id+'\')">'+result[i]['Kelurahan'].nama+'</a></td>'+
									'<td>'+result[i][''].QTPS+'</td>'+
									'<td>'+result[i][''].pt_l+'</td>'+
									'<td>'+result[i][''].pt_p+'</td>'+
									'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
								'</tr>';no++;
							//alert(JSON.stringify(result[i][j].nama));
							
						}
						$('#tpsPage-kelurahan-table tbody').html(baruHtml);
						$('#tpsPage-kelurahan-table').table( 'rebuild' );
						
					}
				}
			});                 
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kelurahan_input&id_kecamatan='+tpsPage.kecamatanS.id,
				data: $('#tpsPage-kelurahan-form-input').serialize(),
				success: function (result) {
					if(result.status) {	
						$('#tpsPage-kelurahan-popup-input').popup( 'close' );
						$('#tpsPage-kelurahan-form-input')[0].reset();
						tpsPage.kelurahan._read(tpsPage.kecamatanS.id);
					} 
				}
			});
		},
		
		_pFI : function(){
			$('#tpsPage-kelurahan-popup-input h3').html('Input Data kelurahan');
			$('#tpsPage-kelurahan-form-input')[0].reset();
			$('#tpsPage-kelurahan-form-input').attr('onsubmit','tpsPage.kelurahan._input(); return false;');
		}, 
	},
	
	kecamatan : {
		_init : function(id_kabupaten){
			tpsPage.kecamatan._tab(id_kabupaten);
			tpsPage.kecamatan._read(id_kabupaten);
		},

		_tab : function(id_kabupaten) {
			$('#tpsPage-tab-control').html(
					'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="tpsPage.tab._kabupaten()" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<select id="tpsPage-kabupaten-select" onchange="tpsPage.tab._kecamatan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#tpsPage-tab-control').trigger('create');
			tpsPage.kabupaten._select(id_kabupaten);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kecamatan_read&id_kabupaten='+tpsPage.kabupatenS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kecamatan -</option>'; 
					for (var i in result){
						if(result[i]['Kecamatan'].id == selected){
							tpsPage.kecamatanS = {'id':result[i]['Kecamatan'].id,'nama':result[i]['Kecamatan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kecamatan'].id+'">'+result[i]['Kecamatan'].nama+'</option>';
						//alert(JSON.stringify(result[i][Kecamatan].nama));
					}
					$('#tpsPage-kecamatan-select').html(baruHtml);
					$('#tpsPage-kecamatan-select').val(selected).selectmenu('refresh');
				}
			});
		},
		
		_read : function(id_kabupaten) {
			$('#tpsPage-kecamatan-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status==false){
						$('#tpsPage-kecamatan-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
						$('#tpsPage-kecamatan-table').table( 'rebuild' );
					} else {
						//alert(JSON.stringify(result)); 
						var no=1;
						for (var i in result){
							baruHtml += '<tr><td>'+no+'</td>'+
								'<td><a href="#" onclick="tpsPage.tab._kelurahan(\''+result[i]['Kecamatan'].id+'\')">'+result[i]['Kecamatan'].nama+'</a></td>'+
								'<td>'+result[i][''].Qkelurahan+'</td>'+
								'<td>'+result[i][''].QTPS+'</td>'+
								'<td>'+result[i][''].pt_l+'</td>'+
								'<td>'+result[i][''].pt_p+'</td>'+
								'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
								'</tr>';no++;
							//alert(JSON.stringify(result[i][j].nama));
						}
						$('#tpsPage-kecamatan-table tbody').html(baruHtml);
						$('#tpsPage-kecamatan-table').table( 'rebuild' );	
					}
				}
			});              
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kecamatan_input&id_kabupaten='+tpsPage.kabupatenS.id,
				data: $('#tpsPage-kecamatan-form-input').serialize(),
				success: function (result) {
					if(result.status) {	
						$('#tpsPage-kecamatan-popup-input').popup( 'close' );
						$('#tpsPage-kecamatan-form-input')[0].reset();
						tpsPage.kecamatan._read(tpsPage.kabupatenS.id);
					} 
				}
			});
		},
		
		_pFI : function(){
			$('#tpsPage-kecamatan-popup-input h3').html('Input Data kecamatan');
			$('#tpsPage-kecamatan-form-input')[0].reset();
			$('#tpsPage-kecamatan-form-input').attr('onsubmit','tpsPage.kecamatan._input(); return false;');
		}, 
	},
	
	kabupaten : {
		_init : function(){
			tpsPage.kabupaten._tab();
			tpsPage.kabupaten._read();
		},
		
		_tab : function() {
			$('#tpsPage-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
				'</fieldset>');
			$('#tpsPage-tab-control').trigger('create');
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kabupaten_read',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kabupaten -</option>'; 
					for (var i in result){
						if(result[i]['Kabupaten'].id == selected){
							tpsPage.kabupatenS = {'id':result[i]['Kabupaten'].id,'nama':result[i]['Kabupaten'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kabupaten'].id+'">'+result[i]['Kabupaten'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kabupaten'].nama));
					}
					$('#tpsPage-kabupaten-select').html(baruHtml);
					$('#tpsPage-kabupaten-select').val(selected).selectmenu('refresh');
				}
			});      
		},
		
		_read : function() {
			$('#tpsPage-kabupaten-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kabupaten_read',
				success: function (result) {
					var baruHtml = ''; 
					var no=1;
					for (var i in result){
						baruHtml += '<tr><th>'+no+'</th>'+
								'<td><a href="#" onclick="tpsPage.tab._kecamatan(\''+result[i]['Kabupaten'].id+'\')">'+result[i]['Kabupaten'].nama+'</a></td>'+
								'<td>'+result[i][''].Qkecamatan+'</td>'+
								'<td>'+result[i][''].QTPS+'</td>'+
								'<td>'+result[i][''].pt_l+'</td>'+
								'<td>'+result[i][''].pt_p+'</td>'+
								'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
							'</tr>'; no++;
						//alert(JSON.stringify(result[i][j].nama));
					}
					$('#tpsPage-kabupaten-table tbody').html(baruHtml);
					$('#tpsPage-kabupaten-table').table( 'rebuild' );
				}
			});
			
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: UChost+''+tpsPage.server+'c=kabupaten_input',
				data: $('#tpsPage-kabupaten-form-input').serialize(),
				success: function (result) {
					if(result.status) {	
						$('#tpsPage-kabupaten-popup-input').popup( 'close' );
						$('#tpsPage-kabupaten-form-input')[0].reset();
						tpsPage.kabupaten._read();
					} 
				}
			});
		},
		
		_pFI : function(){
			$('#tpsPage-kabupaten-popup-input h3').html('Input Data kabupaten');
			$('#tpsPage-kabupaten-form-input')[0].reset();
			$('#tpsPage-kabupaten-form-input').attr('onsubmit','tpsPage.kabupaten._input(); return false;');
		}, 
		
	},
};

var report 	= {	
	server 		: '_control/admin/report.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/report.php?ajax&',
		
	kesalahan : {
		
		_warning : function() {
			var baruHtml = '';
			
			ajaxP.Request({
				url: UChost+''+report.server+'c=report_warning',
				success: function (result) {
					$('#report-warning').html(baruHtml);
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result)); 
						var kabupaten_seb = "";
						var Qkabupaten_seb = 1;
						var kecamatan_seb = "";
						var Qkecamatan_seb = 1;
						var arrayId = [];
						var arrayVal = [];
						var kab_no=1;
						var kel_no=1;
						for (var i in result){
							if(result[i]['Kecamatan'].id == window.localStorage.getItem("rcuserID_WIL")){
								
								if(result[i]['Kecamatan'].id != kecamatan_seb){
									baruHtml += '<li data-role="list-divider">Kecamatan '+result[i]['Kecamatan'].nama+'<span class="ui-li-count" id="kel-warning-'+kel_no+'"></span></li>';
									arrayId.push('kel-warning-'+(kel_no-1));
									arrayVal.push(Qkecamatan_seb);
									Qkecamatan_seb = 0;
									kel_no++;
								}
								baruHtml += '<li><a href="#report-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" onclick="report.tps._readUpdate(\''+result[i]['Tbl'].id+'\');">'+
									'<h3>TPS '+result[i]['Tbl'].no+' Kelurahan '+result[i]['Kelurahan'].nama+'</h3>'+
									'<p> '+result[i]['Tbl'].p_tetap+' : Jumlah pemilih tetap ,<br/> '+result[i]['Tbl'].suara_masuk+' : jumlah suara masuk</p>'+
									'<p class="ui-li-aside"><strong></strong></p>'+
								'</a></li>';
								kabupaten_seb = result[i]['Kabupaten'].id;
								kecamatan_seb = result[i]['Kecamatan'].id;
								Qkabupaten_seb++;
								Qkecamatan_seb++;
								/*baruHtml += '<li>Pemilih Tetap : '+result[i]['Tbl'].p_tetap+' | Suara Masuk : '+result[i]['Tbl'].suara_masuk+
								' pada <a href="#report-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" onclick="report.tps._readUpdate(\''+result[i]['Tbl'].id+'\');" href="#"> TPS '+
								result[i]['Tbl'].no+' (Alamat : '+result[i]['Tbl'].alamat+', Kelurahan '+result[i]['Kelurahan'].nama+', Kecamatan '+
								result[i]['Kecamatan'].nama+', Kabupaten/Kota '+result[i]['Kabupaten'].nama+')</a></li>';
								//alert(JSON.stringify(result[i][j].nama));
								*/
							}
						}
						arrayId.push('kel-warning-'+(kel_no-1));
						arrayVal.push(Qkecamatan_seb);
						$('#report-warning').html(baruHtml);
						$('#report-warning').listview("refresh");
						
						for(var j=0; j<arrayId.length; j++){
							console.log(arrayId[j]+'='+arrayVal[j]);
							$('#'+arrayId[j]).html(arrayVal[j]);
						}
					}
				}
			});                 
		},
		
		_error : function() {
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+report.server+'c=report_error',
				success: function (result) {
					$('#report-error').html(baruHtml);
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result)); 
						var kabupaten_seb = "";
						var Qkabupaten_seb = 1;
						var kecamatan_seb = "";
						var Qkecamatan_seb = 1;
						var arrayId = [];
						var arrayVal = [];
						var kab_no=1;
						var kel_no=1;
						for (var i in result){
							if(result[i]['Kecamatan'].id == window.localStorage.getItem("rcuserID_WIL")){
								
								if(result[i]['Kecamatan'].id != kecamatan_seb){
									baruHtml += '<li data-role="list-divider" data-theme="b">Kecamatan '+result[i]['Kecamatan'].nama+'<span class="ui-li-count" id="kel-error-'+kel_no+'"></span></li>';
									arrayId.push('kel-error-'+(kel_no-1));
									arrayVal.push(Qkecamatan_seb);
									Qkecamatan_seb = 0;
									kel_no++;
								}
								baruHtml += '<li><a href="#report-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" onclick="report.tps._readUpdate(\''+result[i]['Tbl'].id+'\');">'+
									'<h2>TPS '+result[i]['Tbl'].no+' Kelurahan '+result[i]['Kelurahan'].nama+'</h2>'+
									'<p>'+result[i]['Tbl'].digunakan+' : Jumlah surat suara terpakai menurut TPS ,<br/>'+result[i]['Tbl'].suara_masuk+' : jumlah suara sah + tdk sah</p>'+
									'<p class="ui-li-aside"><strong></strong></p>'+
								'</a></li>';
								kabupaten_seb = result[i]['Kabupaten'].id;
								kecamatan_seb = result[i]['Kecamatan'].id;
								Qkabupaten_seb++;
								Qkecamatan_seb++;
								/*baruHtml += '<li>Pemilih Tetap : '+result[i]['Tbl'].p_tetap+' | Suara Masuk : '+result[i]['Tbl'].suara_masuk+
								' pada <a href="#report-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" onclick="report.tps._readUpdate(\''+result[i]['Tbl'].id+'\');" href="#"> TPS '+
								result[i]['Tbl'].no+' (Alamat : '+result[i]['Tbl'].alamat+', Kelurahan '+result[i]['Kelurahan'].nama+', Kecamatan '+
								result[i]['Kecamatan'].nama+', Kabupaten/Kota '+result[i]['Kabupaten'].nama+')</a></li>';
								//alert(JSON.stringify(result[i][j].nama));
								*/
							}
						}
						
						arrayId.push('kel-error-'+(kel_no-1));
						arrayVal.push(Qkecamatan_seb);
						$('#report-error').html(baruHtml);
						$('#report-error').listview("refresh");
						
						for(var j=0; j<arrayId.length; j++){
							//console.log(arrayId[j]+'='+arrayVal[j]);
							$('#'+arrayId[j]).html(arrayVal[j]);
						}
					}
				}
			});                 
		},
	},
	
	tps : {
		_init : function(id_kelurahan){
			report.tps._tab();
		},

		_tab : function(id_kelurahan) {
			
			ajaxP.Request({
				url: UChost+''+report.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						
						//alert(JSON.stringify(result)); 
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
								
							}
							
						}
						$('#report-tps-form-input-hitung').html(formhitung);
						$('#report-tps-form-input').trigger('create');
					} else {
						
					}
				},
			});
			
		},
		
		_update : function() {
    
			ajaxP.Request({
				url: UChost+''+report.server+'c=tps_update',
				data: $('#report-tps-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						$('#report-tps-popup-input').popup( 'close' );
						$('#report-tps-form-input')[0].reset();
						report.kesalahan._warning();
						report.kesalahan._error();
					}
				},
			});                   
        },
		
		_readUpdate : function(id_tps) {
			$('#report-tps-no').html();
			$('#report-tps-form-input')[0].reset();
			ajaxP.Request({
				url: UChost+''+report.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					report.tps._pFU();
					$('#report-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#report-tps-no').html(result[0]['Tp'].no);
					$('#report-tps-pt').html((parseFloat(result[0]['Tp'].pt_l)+parseFloat(result[0]['Tp'].pt_p)));
					$('#report-tps-form-input input[name="tdk_sah"]').val(result[0]['Tp'].tdk_sah);
					$('#report-tps-form-input input[name="digunakan"]').val(result[0]['Tp'].digunakan);
					
					for (var i in result){
						$('#report-tps-form-input input[name="no-urut-'+result[i]['Hitung'].id_calon+'"]').val(result[i]['Hitung'].suara);
						//alert(JSON.stringify(result[i]['Hitung'].id_calon));
					}
					
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
		},
		
		_pFU : function(id){
			$('#report-tps-popup-input h3').html('Edit Data Pemungutan Suara');
			$('#report-tps-form-input').attr('onsubmit','report.tps._update(); return false;');
		},
	
	},
};

var index 	= {
	server 		: '_control/admin/index.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/index.php?ajax&',
		
	kabupatenS : {},
	kecamatanS : {},
	kelurahanS : {},
	
	akses : false,
	
	logout : function () {
		window.localStorage.removeItem("rcuserID");
		window.localStorage.removeItem("rcuserNAME");
		window.localStorage.removeItem("rcuserLEVEL");
		window.localStorage.removeItem("rcuserID_WIL");
		window.localStorage.setItem("rclogIN", 0);
		window.location = "index.html#login";
	},
	
	tab : {
		
		_sumbar : function(){
			index.tab._closeall();
			$('#index-sumbar').show();
			index.sumbar._init();
			
		},
		
		_kabupaten : function(){
			index.tab._closeall();
			$('#index-kabupaten').show();
			index.kabupaten._init();
		},
		
		_kecamatan : function(id_kabupaten){
			
			index.tab._closeall();
			$('#index-kecamatan').show();
			index.kecamatan._init(id_kabupaten);
		},
		
		_kelurahan : function(id_kecamatan){
			if(window.localStorage.getItem("rcuserID_WIL")==id_kecamatan){
				index.akses = true;
			}else{
				index.akses = false;
			}
			index.tab._closeall();
			$('#index-kelurahan').show();
			index.kelurahan._init(id_kecamatan);
		},
		
		_tps : function(id_kelurahan){
			index.tab._closeall();
			$('#index-tps').show();
			index.tps._init(id_kelurahan);
		},
		
		_closeall : function(){
			$('#index-sumbar').hide();
			$('#index-kabupaten').hide();
			$('#index-kecamatan').hide();
			$('#index-kelurahan').hide();
			$('#index-tps').hide();
		},
	},
	
	tps : {
		_init : function(id_kelurahan){
			index.tps._tab(id_kelurahan);
			index.tps._read(id_kelurahan);
		},

		_tab : function(id_kelurahan) {
			$('#index-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="index.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="index.tab._kabupaten();" class="ui-shadow ui-btn ui-icon-arrow-r ui-btn-icon-right ui-corner-all" >Kabupaten/Kota</a>'+
					'<a href="#" onclick="index.tab._kecamatan('+index.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+index.kabupatenS.nama+'</a>'+
					'<a href="#" onclick="index.tab._kelurahan('+index.kecamatanS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+index.kecamatanS.nama+'</a>'+
					'<select id="index-kelurahan-select" onchange="index.tab._tps(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#index-tab-control').trigger('create');
			ajaxP.Request({
				url: UChost+''+index.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>No TPS</th><th data-priority="7">Alamat</th><th data-priority="2">Pemilih Tetap</th><th data-priority="5">Suara Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="4">Suara Sah</th><th data-priority="3">Golput</th><th data-priority="6">Input</th></tr>';
						$('#index-tps-table thead').html(thead);
						$('#index-tps-form-input-hitung').html(formhitung);
						$('#index-tps-form-input').trigger('create');
					}
				},
			});
			index.kelurahan._select(id_kelurahan);
		},
		
		_update : function() {
    
			ajaxP.Request({
				url: UChost+''+index.server+'c=tps_update',
				data: $('#index-tps-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						$('#index-tps-popup-input').popup( 'close' );
						$('#index-tps-form-input')[0].reset();
						index.tps._read(index.kelurahanS.id);
					}
				},
			});                   
        },
		
		_readUpdate : function(id_tps) {
			login.form.checkaktivasi();
			$('#index-tps-no').html();
			$('#index-tps-kelurahan').html();
			$('#index-tps-form-input')[0].reset();
			$('#index-tps-pt').html();
			ajaxP.Request({
				url: UChost+''+index.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					index.tps._pFU();
					$('#index-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#index-tps-no').html(result[0]['Tp'].no);
					$('#index-tps-kelurahan').html(index.kelurahanS.nama);
					$('#index-tps-pt').html('Pemilih Tetap : '+(parseFloat(result[0]['Tp'].pt_l)+parseFloat(result[0]['Tp'].pt_p)));
					$('#index-tps-form-input input[name="tdk_sah"]').val(result[0]['Tp'].tdk_sah);
					$('#index-tps-form-input input[name="digunakan"]').val(result[0]['Tp'].digunakan);
					
					for (var i in result){
						$('#index-tps-form-input input[name="no-urut-'+result[i]['Hitung'].id_calon+'"]').val(result[i]['Hitung'].suara);
						//alert(JSON.stringify(result[i]['Hitung'].id_calon));
					}
					
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
		},
		
		_read : function(id_kelurahan) {
			
			$('#index-tps').html('<br/>&nbsp  Loading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost +''+index.server+'c=tps_read&id_kelurahan='+id_kelurahan,
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_tps = 0;
						var no = 1;
						var data = [];
						$('#index-tps').html('');
						var huruf = "a";
						for (var i in result){
							if(id_tps != result[i]['Tp'].id){
								if(i != 0){
									if(result[i-1]['Hitung'].suara == 0){
										$('#'+result[i-1]['Tp'].id+'-tps-graph').html('<p><br/><br/>Suara Null</p>');
									}else{
										$.plot($('#'+result[i-1]['Tp'].id+'-tps-graph'), data, {
											series: {
												pie: { 
													show: true, radius: 1, //pie
													label: {
														show: true,
														radius: 2/4,
														formatter: function(label, series){
															return '<div style="font-size:7pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:20px; border-radius:15px;">'+
															'<br/>'+label['Calon'].alias+' '+(parseFloat(label['Hitung'].suara)/parseFloat(label['Tp'].sah)*100).toFixed(2)+'% <br/>'+label['Hitung'].suara+'</div>';
														},
														//threshold: 0.1,
													},
													combine: { color: '#999', threshold: 0.1}
												}
											},
											legend: { show: false }
										});
									}
								}
								
								var elementA = 'class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;"';
								if(index.akses == true){
									elementA += 'onclick="index.tps._readUpdate(\''+result[i]['Tp'].id+'\');" href="#index-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup"';
								}else {
									elementA += 'onclick="alert(\'Akses tidak diizinkan untuk kecamatan ini.\');" ';
								}
								$('#index-tps').append('<div class="ui-corner-all ui-block-'+huruf+' custom-corners">'+
									'<a '+elementA+' ><p class="aside">TPS '+result[i]['Tp'].no+' </p>'+
									'<div id="'+result[i]['Tp'].id+'-tps-graph" class="graph-2" style="height:110px;" ></div></a></div>');
								no++;
								data = [];
								x=0;
								if(huruf=="a"){
									huruf="b";
								}else if(huruf=="b"){
									huruf="c";
								}else{
									huruf="a";
								}
							} 
				
							data[x++] = { data: (parseFloat(result[i]['Hitung'].suara)/parseFloat(result[i]['Tp'].sah)*100).toFixed(2), label:result[i]  };
							
							//alert(JSON.stringify(data));
							id_tps = result[i]['Tp'].id;
						}
						if(result[i]['Hitung'].suara == 0){
							$('#'+result[i]['Tp'].id+'-tps-graph').html('<p><br/><br/>Suara Null</p>');
						}else{
							$.plot($('#'+result[i]['Tp'].id+'-tps-graph'), data, {
								series: {
									pie: { 
										show: true, radius: 1, //pie
										label: {
											show: true,	
											radius: 2/4,
											formatter: function(label, series){
												return '<div style="font-size:7pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:20px; border-radius:15px;">'+
												'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label['Hitung'].suara)/parseFloat(label['Tp'].sah)*100).toFixed(2)+'%<br/>'+label['Hitung'].suara+'</div>';
											},
											threshold: 0.1,
										},
										combine: { color: '#999', threshold: 0.1}
									}
								},
								legend: { show: false }
							});
						}	
					}
					index.kelurahan._check();
				}
			});                 
		},
		
		_pFU : function(id){
			$('#index-tps-popup-input h3').html('Edit Data Pemungutan Suara');
			$('#index-tps-form-input').attr('onsubmit','index.tps._update(); return false;');
		},
	},
	
	kelurahan : {
		_init : function(id_kecamatan){
			index.kelurahan._tab(id_kecamatan);
			index.kelurahan._read(id_kecamatan);
		},

		_tab : function(id_kecamatan) {
			$('#index-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="index.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="index.tab._kabupaten();" class="ui-shadow ui-btn ui-icon-arrow-r ui-btn-icon-right ui-corner-all" >Kabupaten/Kota</a>'+
					'<a href="#" onclick="index.tab._kecamatan('+index.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+index.kabupatenS.nama+'</a>'+
					'<select id="index-kecamatan-select" onchange="index.tab._kelurahan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#index-tab-control').trigger('create');
			index.kecamatan._select(id_kecamatan);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+index.server+'c=kelurahan_select&id_kecamatan='+index.kecamatanS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kelurahan -</option>'; 
					for (var i in result){
						if(result[i]['Kelurahan'].id == selected){
							index.kelurahanS = {'id':result[i]['Kelurahan'].id,'nama':result[i]['Kelurahan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kelurahan'].id+'">'+result[i]['Kelurahan'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kelurahan'].nama));
					}
					$('#index-kelurahan-select').html(baruHtml);
					$('#index-kelurahan-select').val(selected).selectmenu('refresh');
				}
			});               
		},
		
		_read : function(id_kecamatan) {
			$('#index-kelurahan').html('<br/>&nbsp  Loading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+index.server+'c=kelurahan_read&id_kecamatan='+id_kecamatan,
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_kelurahan = 0;
						var no = 1;
						var data = [];
						$('#index-kelurahan').html('');
						var huruf= 'a';
						for (var i in result){
							if(id_kelurahan != result[i]['Kelurahan'].id){
								if(i != 0){
									if(result[i-1][''].suara == 0){
										$('#'+result[i-1]['Kelurahan'].id+'-kelurahan-graph').html('<p><br/><br/>Suara Masih Null</p>');
									}else{
										$.plot($('#'+result[i-1]['Kelurahan'].id+'-kelurahan-graph'), data, {
											series: {
												pie: { 
													show: true, radius: 1, //pie
													label: {
														show: true,
														radius: 2/4,
														formatter: function(label, series){
															return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
															'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%<br/>'+label[''].suara+'</div>';
														},
														threshold: 0.1,
													},
													combine: { color: '#999', threshold: 0.1}
												}
											},
											legend: { show: false }
										});
									}	
								}
								
								$('#index-kelurahan').append('<div class="ui-block-'+huruf+' ui-corner-all">'+
									'<a href="#" onclick="index.tab._tps('+result[i]['Kelurahan'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
									'<p class="aside">kel. '+result[i]['Kelurahan'].nama+'</p>'+
									'<div id="'+result[i]['Kelurahan'].id+'-kelurahan-graph" class="graph-2" ></div></a></div>');
								
								no++;
								data = [];
								x=0;
								if(huruf=="a"){
									huruf="b";
								}else{
									huruf="a";
								}
								
							} 
				
							data[x++] = { data: (parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2), label:result[i]  };
							
							//alert(JSON.stringify(data));
							id_kelurahan = result[i]['Kelurahan'].id;
						}
						if(result[i][''].suara == 0){
							$('#'+result[i]['Kelurahan'].id+'-kelurahan-graph').html('<p><br/><br/>Suara Masih Null</p>');
						}else{
							$.plot($('#'+result[i]['Kelurahan'].id+'-kelurahan-graph'), data, {
								series: {
									pie: { 
										show: true, radius: 1, //pie
										label: {
											show: true,
											radius: 2/4,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
												'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%<br/>'+label[''].suara+'</div>';
											},
											threshold: 0.1,
										},
										combine: { color: '#999', threshold: 0.1}
									}
								},
								legend: { show: false }
							});
						}	
					}
					index.kecamatan._check();
				}
			});                 
		},
		
		_check : function() {
			ajaxP.Request({
				url: UChost+''+index.server+'c=kelurahan_check&id_kelurahan='+index.kelurahanS.id,
				success: function (result) {
					if(result!= "0"){
						alert('Info : Jumlah TPS yang belum menginputkan suara pada Kelurahan '+index.kelurahanS.nama+' adalah : '+result);
					}
					
				}
			});      
		},
	},
	
	kecamatan : {
		_init : function(id_kabupaten){
			index.kecamatan._tab(id_kabupaten);
			index.kecamatan._read(id_kabupaten);
		},

		_tab : function(id_kabupaten) {
			$('#index-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="index.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="index.tab._kabupaten();" class="ui-shadow ui-icon-arrow-r ui-btn-icon-right ui-btn ui-corner-all" >Kabupaten/Kota</a>'+
					'<select id="index-kabupaten-select" onchange="index.tab._kecamatan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#index-tab-control').trigger('create');
			index.kabupaten._select(id_kabupaten);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+index.server+'c=kecamatan_select&id_kabupaten='+index.kabupatenS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kecamatan -</option>'; 
					for (var i in result){
						if(result[i]['Kecamatan'].id == selected){
							index.kecamatanS = {'id':result[i]['Kecamatan'].id,'nama':result[i]['Kecamatan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kecamatan'].id+'">'+result[i]['Kecamatan'].nama+'</option>';
						//alert(JSON.stringify(result[i][Kecamatan].nama));
					}
					$('#index-kecamatan-select').html(baruHtml);
					$('#index-kecamatan-select').val(selected).selectmenu('refresh');
				}
			});
		},
		
		_read : function(id_kabupaten) {
			$('#index-kecamatan').html('<br/>&nbsp  Loading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+index.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_kecamatan = 0;
						var no = 1;
						var data = [];
						$('#index-kecamatan').html('');
						var huruf="a";
						for (var i in result){
							if(id_kecamatan != result[i]['Kecamatan'].id){
								if(i != 0){
									if(result[i-1][''].suara == 0){
										$('#'+result[i-1]['Kecamatan'].id+'-kecamatan-graph').html('<p><br/><br/><br/>Suara Masih Null</p>');
									}else{
										$.plot($('#'+result[i-1]['Kecamatan'].id+'-kecamatan-graph'), data, {
											series: {
												pie: { 
													show: true, radius: 1, //pie
													label: {
														show: true,	
														radius: 2/4,
														formatter: function(label, series){
															return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
															'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%<br/>'+label[''].suara+'</div>';
														},
														threshold: 0.1,
													},
													combine: { color: '#999', threshold: 0.1}
												}
											},
											legend: { show: false }
										});
									}
								}
								
								var warnaaside = "";
								if(window.localStorage.getItem("rcuserID_WIL")==result[i]['Kecamatan'].id){warnaaside = "background:#7FFFAA";}
								$('#index-kecamatan').append('<div class="ui-block-'+huruf+'">'+
									'<a href="#" onclick="index.tab._kelurahan('+result[i]['Kecamatan'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
									'<p class="aside" style="'+warnaaside+'">kec. '+result[i]['Kecamatan'].nama+'</p>'+
									'<div id="'+result[i]['Kecamatan'].id+'-kecamatan-graph" class="graph-2" ></div></a></div>');
								
								no++;
								data = [];
								x=0;
								if(huruf=="a"){
									huruf="b";
								}else{
									huruf="a";
								}
							} 
							
							
							data[x++] = { data: (parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2), label:result[i]  };
							
							//alert(JSON.stringify(data));
							id_kecamatan = result[i]['Kecamatan'].id;
						}
						if(result[i][''].suara == 0){
							$('#'+result[i]['Kecamatan'].id+'-kecamatan-graph').html('<p><br/><br/>Suara Masih Null</p>');
						}else{
							$.plot($('#'+result[i]['Kecamatan'].id+'-kecamatan-graph'), data, {
								series: {
									pie: { 
										show: true, radius: 1, //pie
										label: {
											show: true,
											radius: 2/4,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
												'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%<br/>'+label[''].suara+'</div>';
											},
											threshold: 0.1,
										},
										combine: { color: '#999', threshold: 0.1}
									}
								},
								legend: { show: false }
							});
						}
					}
					index.kabupaten._check();
				}
			});              
		},
		
		_check : function() {
			ajaxP.Request({
				url: UChost+''+index.server+'c=kecamatan_check&id_kecamatan='+index.kecamatanS.id,
				success: function (result) {
					if(result!= "0"){
						alert('Info : Jumlah TPS yang belum menginputkan suara pada Kecamatan '+index.kecamatanS.nama+' adalah : '+result);
					}
				}
			});      
		},
	},
	
	kabupaten : {
		_init : function(){
			index.kabupaten._tab();
			index.kabupaten._read();
		},
		
		_tab : function() {
			$('#index-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="index.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="index.tab._kabupaten();" class="ui-shadow ui-btn ui-corner-all" >Kabupaten/Kota</a>'+
				'</fieldset>');
			$('#index-tab-control').trigger('create');
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+index.server+'c=kabupaten_select',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kabupaten -</option>'; 
					for (var i in result){
						if(result[i]['Kabupaten'].id == selected){
							index.kabupatenS = {'id':result[i]['Kabupaten'].id,'nama':result[i]['Kabupaten'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kabupaten'].id+'">'+result[i]['Kabupaten'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kabupaten'].nama));
					}
					$('#index-kabupaten-select').html(baruHtml);
					$('#index-kabupaten-select').val(selected).selectmenu('refresh');
				}
			});      
		},
		
		_read : function() {
			$('#index-kabupaten').html('<br/>&nbsp Loading...<br/><img src="jquery-mobile/images/20.gif" />');
			ajaxP.Request({
				url: UChost+''+index.server+'c=kabupaten_read',
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_kabupaten = 0;
						var no = 1;
						var data = [];
						$('#index-kabupaten').html('');
						var huruf= 'a';
						for (var i in result){
							if(id_kabupaten != result[i]['Kabupaten'].id){
								if(i != 0){
									if(result[i-1][''].suara == 0){
										$('#'+result[i-1]['Kabupaten'].id+'-kabupaten-graph').html('<p><br/><br/><br/>Suara Masih Null</p>');
									}else{
										$.plot($('#'+result[i-1]['Kabupaten'].id+'-kabupaten-graph'), data, {
											series: {
												pie: { 
													show: true, radius: 1, //pie
													label: {
														show: true,	
														radius: 2/4,
														formatter: function(label, series){
															return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
															'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%<br/>'+label[''].suara+'</div></div>';
														},
														threshold: 0.1,
													},
													combine: { color: '#999', threshold: 0.1}
												}
											},
											legend: { show: false }
										});
									}
								}
								
								var warnaaside = "";
								//if(window.localStorage.getItem("rcuserID_WIL")==result[i]['Kabupaten'].id){warnaaside = "background:#7FFFAA";}
								$('#index-kabupaten').append('<div class="ui-block-'+huruf+'">'+
									'<a href="#" onclick="index.tab._kecamatan('+result[i]['Kabupaten'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
									'<p class="aside" style="'+warnaaside+'">'+result[i]['Kabupaten'].nama+'</p>'+
									'<div id="'+result[i]['Kabupaten'].id+'-kabupaten-graph" class="graph-2" ></div></a></div>');
								
								no++;
								data = [];
								x=0;
								if(huruf=="a"){
									huruf="b";
								}else{
									huruf="a";
								}
								
							} 
							
							
							data[x++] = { data: (parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2), label:result[i]  };
							
							//alert(JSON.stringify(data));
							id_kabupaten = result[i]['Kabupaten'].id;
						}
						if(result[i][''].suara == 0){
							$('#'+result[i]['Kabupaten'].id+'-kabupaten-graph').html('<p><br/><br/>Suara Masih Null</p>');
						}else{
							$.plot($('#'+result[i]['Kabupaten'].id+'-kabupaten-graph'), data, {
								series: {
									pie: { 
										show: true, radius: 1, //pie
										label: {
											show: true,	
											radius: 2/4,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
												'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%<br/>'+label[''].suara+'</div></div>';
											},
											threshold: 0.1,
										},
										combine: { color: '#999', threshold: 0.1}
									}
								},
								legend: { show: false }
							});
						}
					}
					
				}
			});
			index.sumbar._check();
		},
		
		_check : function() {
			ajaxP.Request({
				url: UChost+''+index.server+'c=kabupaten_check&id_kabupaten='+index.kabupatenS.id,
				success: function (result) {
					if(result!= "0"){
						alert('Info : Jumlah TPS yang belum menginputkan suara pada Kabupaten/Kota '+index.kabupatenS.nama+' adalah : '+result);
					}
				}
			});      
		},
	},
	
	sumbar : {
		_init : function(){
			index.sumbar._tab();
			index.sumbar._read();
			
		},
		
		_tab : function() {
			$('#index-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#index-kabupaten" onclick="index.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all" >Sumatera Barat</a>'+
				'</fieldset>');
			$('#index-tab-control').trigger('create');
		},
		
		_read : function() {
			
			ajaxP.Request({
				url: UChost+''+index.server+'c=sumbar_read',  
				success: function (result) {
					if(result.status==false){

					} else {
					
						//alert(JSON.stringify(result));
						var data = [];
						/*var series = Math.floor(Math.random()*10)+1;
						for( var i = 0; i<series; i++)
						{
							data[i] = { label: "Series"+(i+1), data: Math.floor(Math.random()*100)+1 }
						}*/
						for (var i in result){
							data[i] = { data: (parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2), label:result[i]  }
						}
						$('#index-graph1').html('');
						//alert(JSON.stringify(data));
						if(result[i][''].suara == 0){
							$('#index-graph1').html('<p><br/><br/><br/>Suara Masih Null</p>');
						} else { 
							$.plot($('#index-graph1'), data, {
								series: {
									pie: { 
										show: true, radius: 1, 
										label: {
											show: true,	radius: 2/4,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+UChost+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:110px; border-radius:7px;">'+
												'<br/><h1>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%</h1>'+label[''].suara+' suara</div>';
											},
											background: { opacity: 0.6 }
										},
										combine: { color: '#999', threshold: 0.1}
									}
								},
								legend: { show: false }
							});
						}
					}
				}
			});
			
		},
		
		_check : function() {
			ajaxP.Request({
				url: UChost+''+index.server+'c=sumbar_check',
				success: function (result) {
					if(result!= "0"){
						alert('Info : Jumlah TPS yang belum menginputkan suara : '+result);
					}
				}
			});      
		},
	},
	
};

var hitung 	= {
	server 		: '_control/admin/hitung.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/hitung.php?ajax&',
	kabupatenS  : {},
	kecamatanS 	: {},
	kelurahanS	: {},
	
	akses : false,
	
	tab : {
			
		_kabupaten : function(){
			hitung.tab._closeall();
			hitung.kabupaten._init();
			$('#hitung-kabupaten').show();
		},
		
		_kecamatan : function(id_kabupaten){
			
			hitung.tab._closeall();
			hitung.kecamatan._init(id_kabupaten);
			$('#hitung-kecamatan').show();
			
		},
		
		_kelurahan : function(id_kecamatan){
			if(window.localStorage.getItem("rcuserID_WIL")==id_kecamatan){
				hitung.akses = true;
			}else{
				hitung.akses = false;
			}
			hitung.tab._closeall();
			hitung.kelurahan._init(id_kecamatan);
			$('#hitung-kelurahan').show();
		},
		
		_tps : function(id_kelurahan){
			hitung.tab._closeall();
			hitung.tps._init(id_kelurahan);
			$('#hitung-tps').show();
		},
		
		_closeall : function(){
			$('#hitung-kabupaten').hide();
			$('#hitung-kecamatan').hide();
			$('#hitung-kelurahan').hide();
			$('#hitung-tps').hide();
		},
	},
	
	tps : {
		_init : function(id_kelurahan){
			hitung.tps._tab(id_kelurahan);
			hitung.tps._read(id_kelurahan);
		},

		_tab : function(id_kelurahan) {
			$('#hitung-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="hitung.tab._kabupaten()" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="hitung.tab._kecamatan('+hitung.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+hitung.kabupatenS.nama+'</a>'+
					'<a href="#" onclick="hitung.tab._kelurahan('+hitung.kecamatanS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+hitung.kecamatanS.nama+'</a>'+
					'<select id="hitung-kelurahan-select" onchange="hitung.tab._tps(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#hitung-tab-control').trigger('create');
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>NO.TPS</th><th data-priority="1">Pemilih Tetap</th><th data-priority="2">Suara Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="3">Suara Sah</th><th data-priority="4">Golput</th><th data-priority="5">Suara<br/>sah+tdk sah</th><th data-priority="6">Suara Terpakai<br/>Menurut TPS</th></tr>';
						$('#hitung-tps-table thead').html(thead);
						$('#hitung-tps-form-input-hitung').html(formhitung);
						$('#hitung-tps-form-input').trigger('create');
					}
				},
			});
			hitung.kelurahan._select(id_kelurahan);
		},
		
		_update : function() {
    
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=tps_update',
				data: $('#hitung-tps-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						$('#hitung-tps-popup-input').popup( 'close' );
						$('#hitung-tps-form-input')[0].reset();
						hitung.tps._read(hitung.kelurahanS.id);
					}
				},
			});                   
        },
		
		_readUpdate : function(id_tps) {
			$('#hitung-tps-no').html();
			$('#hitung-tps-kelurahan').html();
			$('#hitung-tps-form-input')[0].reset();
			$('#hitung-tps-pt').html();
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					hitung.tps._pFU();
					$('#hitung-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#hitung-tps-no').html(result[0]['Tp'].no);
					$('#hitung-tps-kelurahan').html(hitung.kelurahanS.nama);
					$('#hitung-tps-pt').html((parseFloat(result[0]['Tp'].pt_l)+parseFloat(result[0]['Tp'].pt_p)));
					
					$('#hitung-tps-form-input input[name="tdk_sah"]').val(result[0]['Tp'].tdk_sah);
					$('#hitung-tps-form-input input[name="digunakan"]').val(result[0]['Tp'].digunakan);
					
					for (var i in result){
						$('#hitung-tps-form-input input[name="no-urut-'+result[i]['Hitung'].id_calon+'"]').val(result[i]['Hitung'].suara);
						//alert(JSON.stringify(result[i]['Hitung'].id_calon));
					}
					
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
		},
		
		_read : function(id_kelurahan) {
			$('#hitung-tps-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=tps_read&id_kelurahan='+id_kelurahan,
				success: function (result) {
					if(result.status==false){
						$('#hitung-tps-table tbody').html(baruHtml);
						$('#hitung-tps-table').table( 'rebuild' );
					} else {
						//alert(JSON.stringify(result)); 
						var id_tps = 0;
						var golput = 0;
						for (var i in result){
						
							if(id_tps != result[i]['Tp'].id){
								if(i != 0){
									if(result[i-1]['Tp'].sah == null || result[i-1]['Tp'].sah== 0){
										baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
									} else {
										baruHtml += '<td>'+result[i-1]['Tp'].sah+'</td><td>'+(golput-parseFloat(result[i-1]['Tp'].sah))+'</td>';
									}
									baruHtml += '<td>'+(parseFloat(result[i-1]['Tp'].sah)+parseFloat(result[i-1]['Tp'].tdk_sah))+'</td><td>'+result[i-1]['Tp'].digunakan+'</td></tr>';
								}
								baruHtml += '<tr>';
								if(hitung.akses == true){
									baruHtml += '<td><a onclick="hitung.tps._readUpdate(\''+result[i]['Tp'].id+'\');" href="#hitung-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a> '+result[i]['Tp'].no+'</td>';			
								}else {
									baruHtml += '<td>'+result[i]['Tp'].no+'</td>';
								}
								baruHtml += '<td>'+(parseFloat(result[i]['Tp'].pt_l)+parseFloat(result[i]['Tp'].pt_p))+'</td>'+
								'<td>'+result[i]['Tp'].tdk_sah+'</td>';
								golput = parseFloat(result[i]['Tp'].pt_l)+parseFloat(result[i]['Tp'].pt_p)-parseFloat(result[i]['Tp'].tdk_sah);
							} 
							baruHtml += '<td>'+(parseFloat(result[i]['Hitung'].suara)/parseFloat(result[i]['Tp'].sah)*100).toFixed(2)+'% - '+result[i]['Hitung'].suara+'</td>';
							//alert(JSON.stringify(result[i]['Tp'].no));
						
							id_tps = result[i]['Tp'].id;
							
						}
						if(result[i]['Tp'].sah==null || result[i]['Tp'].sah== 0 ){
							baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
						} else {
							baruHtml += '<td>'+result[i]['Tp'].sah+'</td><td>'+(golput-parseFloat(result[i]['Tp'].sah))+'</td>';
						}
						baruHtml += '<td>'+(parseFloat(result[i]['Tp'].sah)+parseFloat(result[i]['Tp'].tdk_sah))+'</td><td>'+result[i]['Tp'].digunakan+'</td></tr>';
								
						$('#hitung-tps-table tbody').html(baruHtml);
						$('#hitung-tps-table').table( 'rebuild' );
						
					}
				}
			});                 
		},
		
		_pFI : function(){
			$('#hitung-tps-popup-input h3').html('Input Data tps');
			$('#hitung-tps-form-input')[0].reset();
			$('#hitung-tps-form-input').attr('onsubmit','hitung.tps._input(); return false;');
		}, 
		
		_pFU : function(id){
			$('#hitung-tps-popup-input h3').html('Edit Data Pemungutan Suara');
			$('#hitung-tps-form-input').attr('onsubmit','hitung.tps._update(); return false;');
		},
	
		_pBD : function(id){
			$('#hitung-popup-delete').html('<a onclick="hitung.tps._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		}
	},
	
	kelurahan : {
		_init : function(id_kecamatan){
			hitung.kelurahan._tab(id_kecamatan);
			hitung.kelurahan._read(id_kecamatan);
		},

		_tab : function(id_kecamatan) {
			$('#hitung-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="hitung.tab._kabupaten()" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="hitung.tab._kecamatan('+hitung.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+hitung.kabupatenS.nama+'</a>'+
					'<select id="hitung-kecamatan-select" onchange="hitung.tab._kelurahan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#hitung-tab-control').trigger('create');
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>No</th><th data-priority="7">Kelurahan</th><th data-priority="2">Pemilih<br/>Tetap</th><th data-priority="5">Suara<br/>Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="4">Suara Sah</th><th data-priority="3">Golput</th><th data-priority="6">Suara<br/>sah+tdk sah</th><th data-priority="6">Suara Terpakai<br/>Menurut TPS</th></tr>';
						$('#hitung-kelurahan-table thead').html(thead);
						$('#hitung-kelurahan-form-input-hitung').html(formhitung);
					}
				},
			});
			hitung.kecamatan._select(id_kecamatan);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kelurahan_select&id_kecamatan='+hitung.kecamatanS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kelurahan -</option>'; 
					for (var i in result){
						if(result[i]['Kelurahan'].id == selected){
							hitung.kelurahanS = {'id':result[i]['Kelurahan'].id,'nama':result[i]['Kelurahan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kelurahan'].id+'">'+result[i]['Kelurahan'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kelurahan'].nama));
					}
					$('#hitung-kelurahan-select').html(baruHtml);
					$('#hitung-kelurahan-select').val(selected).selectmenu('refresh');
				}
			});               
		},
		
		_read : function(id_kecamatan) {
			$('#hitung-kelurahan-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kelurahan_read&id_kecamatan='+id_kecamatan,
				success: function (result) {
					if(result.status==false){
						$('#hitung-kelurahan-table tbody').html(baruHtml);
						$('#hitung-kelurahan-table').table( 'rebuild' );
					} else {
						//alert(JSON.stringify(result));
						var id_kelurahan = 0;
						var golput = 0;
						var no = 1;
						for (var i in result){
						
							if(id_kelurahan != result[i]['Kelurahan'].id){
								if(i != 0){
									if(result[i-1][''].sah==null || result[i-1][''].sah==0){
										baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
									} else {
										baruHtml += '<td>'+result[i-1][''].sah+'</td><td>'+(golput-parseFloat(result[i-1][''].sah))+'</td>';
									}
									baruHtml += '<td>'+(parseFloat(result[i-1][''].sah)+parseFloat(result[i-1][''].tdk_sah))+'</td><td>'+result[i-1][''].digunakan+'</td></tr>';
						
									no++;					
								}
								baruHtml += '<tr>'+
								'<th>'+no+'</th>'+
								'<th><a href="#" onclick="hitung.tab._tps(\''+result[i]['Kelurahan'].id+'\')">'+result[i]['Kelurahan'].nama+'</a></th>'+
								'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
								'<td>'+result[i][''].tdk_sah+'</td>';
								golput = parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p)-parseFloat(result[i][''].tdk_sah);
							} 
							baruHtml += '<td>'+(parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2)+'% - '+result[i][''].suara+'</td>';
							
						
							id_kelurahan = result[i]['Kelurahan'].id;
							
						}
						if(result[i][''].sah==null||result[i][''].sah==0){
							baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
						} else {
							baruHtml += '<td>'+result[i][''].sah+'</td><td>'+(golput-parseFloat(result[i][''].sah))+'</td>';
						}
						baruHtml += '<td>'+(parseFloat(result[i][''].sah)+parseFloat(result[i][''].tdk_sah))+'</td><td>'+result[i][''].digunakan+'</td></tr>';
						
						$('#hitung-kelurahan-table tbody').html(baruHtml);
						$('#hitung-kelurahan-table').table( 'rebuild' );
						
					}
				}
			});                 
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kelurahan_input&id_kecamatan='+hitung.kecamatanS.id,
				data: $('#hitung-kelurahan-form-input').serialize(),
				success: function (result) {
					if(result.status) {	
						$('#hitung-kelurahan-popup-input').popup( 'close' );
						$('#hitung-kelurahan-form-input')[0].reset();
						hitung.kelurahan._read(hitung.kecamatanS.id);
					} 
				}
			});
		},
		
		_pFI : function(){
			$('#hitung-kelurahan-popup-input h3').html('Input Data kelurahan');
			$('#hitung-kelurahan-form-input')[0].reset();
			$('#hitung-kelurahan-form-input').attr('onsubmit','hitung.kelurahan._input(); return false;');
		}, 
	},
	
	kecamatan : {
		_init : function(id_kabupaten){
			hitung.kecamatan._tab(id_kabupaten);
			hitung.kecamatan._read(id_kabupaten);
		},

		_tab : function(id_kabupaten) {
			$('#hitung-tab-control').html(
					'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="hitung.tab._kabupaten()" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<select id="hitung-kabupaten-select" onchange="hitung.tab._kecamatan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#hitung-tab-control').trigger('create');
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>No</th><th data-priority="7">Kecamatan</th><th data-priority="2">Pemilih<br/>Tetap</th><th data-priority="5">Suara<br/>Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="4">Suara<br/>Sah</th><th data-priority="3">Golput</th><th data-priority="6">Suara<br/>sah+tdk sah</th><th data-priority="6">Suara Terpakai<br/>Menurut TPS</th></tr>';
						$('#hitung-kecamatan-table thead').html(thead);
						$('#hitung-kecamatan-form-input-hitung').html(formhitung);
					}
				},
			});
			hitung.kabupaten._select(id_kabupaten);
			
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kecamatan_select&id_kabupaten='+hitung.kabupatenS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kecamatan -</option>'; 
					for (var i in result){
						if(result[i]['Kecamatan'].id == selected){
							hitung.kecamatanS = {'id':result[i]['Kecamatan'].id,'nama':result[i]['Kecamatan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kecamatan'].id+'">'+result[i]['Kecamatan'].nama+'</option>';
						//alert(JSON.stringify(result[i][Kecamatan].nama));
					}
					$('#hitung-kecamatan-select').html(baruHtml);
					$('#hitung-kecamatan-select').val(selected).selectmenu('refresh');
				}
			});
		},
		
		_read : function(id_kabupaten) {
			$('#hitung-kecamatan-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status==false){
						$('#hitung-kecamatan-table tbody').html(baruHtml);
						$('#hitung-kecamatan-table').table( 'rebuild' );
					} else {
						//alert(JSON.stringify(result));
						var id_kecamatan = 0;
						var golput = 0;
						var no = 1;
						for (var i in result){
							if(id_kecamatan != result[i]['Kecamatan'].id){
								if(i != 0){
									if(result[i-1][''].sah==null || result[i-1][''].sah==0){
										baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
									} else {
										baruHtml += '<td>'+result[i-1][''].sah+'</td><td>'+(golput-parseFloat(result[i-1][''].sah))+'</td>';
									}
									baruHtml += '<td>'+(parseFloat(result[i-1][''].sah)+parseFloat(result[i-1][''].tdk_sah))+'</td><td>'+result[i-1][''].digunakan+'</td></tr>';
									no++;					
								}
								if(window.localStorage.getItem("rcuserID_WIL")==result[i]['Kecamatan'].id){
									baruHtml += '<tr style="background:#7FFFAA;">';
								}else{
									baruHtml += '<tr>';
								}
								baruHtml += '<th>'+no+'</th>'+
								'<th><a href="#" onclick="hitung.tab._kelurahan(\''+result[i]['Kecamatan'].id+'\')">'+result[i]['Kecamatan'].nama+'</a></th>'+
								'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
								'<td>'+result[i][''].tdk_sah+'</td>';
								golput = parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p)-parseFloat(result[i][''].tdk_sah);
							} 
							
							baruHtml += '<td>'+(parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2)+'% - '+result[i][''].suara+'</td>';
						
							id_kecamatan = result[i]['Kecamatan'].id;
						}
						if(result[i][''].sah==null || result[i][''].sah==0){
							baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
						} else {
							baruHtml += '<td>'+result[i][''].sah+'</td><td>'+(golput-parseFloat(result[i][''].sah))+'</td>';
						}
						baruHtml += '<td>'+(parseFloat(result[i][''].sah)+parseFloat(result[i][''].tdk_sah))+'</td><td>'+result[i][''].digunakan+'</td></tr>';
						$('#hitung-kecamatan-table tbody').html(baruHtml);
						$('#hitung-kecamatan-table').table( 'rebuild' );	
					}
				}
			});              
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kecamatan_input&id_kabupaten='+hitung.kabupatenS.id,
				data: $('#hitung-kecamatan-form-input').serialize(),
				success: function (result) {
					if(result.status) {	
						$('#hitung-kecamatan-popup-input').popup( 'close' );
						$('#hitung-kecamatan-form-input')[0].reset();
						hitung.kecamatan._read(hitung.kabupatenS.id);
					} 
				}
			});
		},
		
		_pFI : function(){
			$('#hitung-kecamatan-popup-input h3').html('Input Data kecamatan');
			$('#hitung-kecamatan-form-input')[0].reset();
			$('#hitung-kecamatan-form-input').attr('onsubmit','hitung.kecamatan._input(); return false;');
		}, 
	},
	
	kabupaten : {
		_init : function(){
			hitung.kabupaten._tab();
			hitung.kabupaten._read();
		},
		
		_tab : function() {
			$('#hitung-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
				'</fieldset>');
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>No</th><th data-priority="7">Kabupaten</th><th data-priority="2">Pemilih<br/>Tetap</th><th data-priority="5">Suara<br/> Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="4">Suara<br/> Sah</th><th data-priority="3">Golput</th><th data-priority="3">Suara<br/>Sah+tdk Sah</th><th data-priority="6">Suara Terpakai<br/> Menurut TPS</th></tr>';
						$('#hitung-kabupaten-table thead').html(thead);
						$('#hitung-kabupaten-form-input-hitung').html(formhitung);
					}
				},
			});
			$('#hitung-tab-control').trigger('create');
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kabupaten_select',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kabupaten -</option>'; 
					for (var i in result){
						if(result[i]['Kabupaten'].id == selected){
							hitung.kabupatenS = {'id':result[i]['Kabupaten'].id,'nama':result[i]['Kabupaten'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kabupaten'].id+'">'+result[i]['Kabupaten'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kabupaten'].nama));
					}
					$('#hitung-kabupaten-select').html(baruHtml);
					$('#hitung-kabupaten-select').val(selected).selectmenu('refresh');
				}
			});      
		},
		
		_read : function() {
			$('#hitung-kabupaten-table tbody').html('<br/> &nbspLoading...<br/><img src="jquery-mobile/images/20.gif" />');
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kabupaten_read',
				success: function (result) {
					if(result.status==false){
						$('#hitung-kabupaten-table tbody').html('');
						$('#hitung-kabupaten-table').table( 'rebuild' );
					} else {
						//alert(JSON.stringify(result));
						var baruHtml ;
						var id_kabupaten = 0;
						var golput = 0;
						var no = 1;
						for (var i in result){
							if(id_kabupaten != result[i]['Kabupaten'].id){
								if(i != 0){
									if(result[i-1][''].sah== null || result[i-1][''].sah== 0){
										baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
									} else {
										baruHtml += '<td>'+result[i-1][''].sah+'</td><td>'+(golput-parseFloat(result[i-1][''].sah))+'</td>';
									}
									baruHtml += '<td>'+(parseFloat(result[i-1][''].sah)+parseFloat(result[i-1][''].tdk_sah))+'</td><td>'+result[i-1][''].digunakan+'</td></tr>';
									no++;
								}
								
								baruHtml += '<tr>';
								baruHtml += '<th>'+no+'</th>'+
								'<th><a href="#" onclick="hitung.tab._kecamatan(\''+result[i]['Kabupaten'].id+'\')">'+result[i]['Kabupaten'].nama+'</a></th>'+
								'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
								'<td>'+result[i][''].tdk_sah+'</td>';
								golput = parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p)-parseFloat(result[i][''].tdk_sah);
							} 
							
							baruHtml += '<td>'+(parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2)+'% - '+result[i][''].suara+'</td>';
						
							id_kabupaten = result[i]['Kabupaten'].id;
						}
						if(result[i][''].sah==null || result[i][''].sah==0){
							baruHtml += '<td colspan="2"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td>';
						} else {
							baruHtml += '<td>'+result[i][''].sah+'</td><td>'+(golput-parseFloat(result[i][''].sah))+'</td>';
						}
						baruHtml += '<td>'+(parseFloat(result[i][''].sah)+parseFloat(result[i][''].tdk_sah))+'</td><td>'+result[i][''].digunakan+'</td></tr>';
						$('#hitung-kabupaten-table tbody').html(baruHtml);
						$('#hitung-kabupaten-table').table( 'rebuild' );
					}
					
				}
			});
			
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: UChost+''+hitung.server+'c=kabupaten_input',
				data: $('#hitung-kabupaten-form-input').serialize(),
				success: function (result) {
					if(result.status) {	
						$('#hitung-kabupaten-popup-input').popup( 'close' );
						$('#hitung-kabupaten-form-input')[0].reset();
						hitung.kabupaten._read();
					} 
				}
			});
		},
		
		_pFI : function(){
			$('#hitung-kabupaten-popup-input h3').html('Input Data kabupaten');
			$('#hitung-kabupaten-form-input')[0].reset();
			$('#hitung-kabupaten-form-input').attr('onsubmit','hitung.kabupaten._input(); return false;');
		}, 
		
	},
};

var admin 	= {
	//server : 'http://localhost/myjob/ipna/_control/admin/admin.php?ajax&',
	server : '_control/admin/admin.php?ajax&',
	level : {},
	kabupatenS : {},
	kecamatanS : {id:window.localStorage.getItem("rcuserID_WIL")},
	
	aktivasi : {
		satu : function(id,status){
			ajaxP.Request({
				url: UChost+''+admin.server+'c=aktivasi_satu&id='+id+'&status='+status,
				success: function (result) {
					
				},
			});
		},
		
		semua : function(level,status){
			ajaxP.Request({
				url: UChost+''+admin.server+'c=aktivasi_semua&level='+level+'&status='+status,
				success: function (result) {
					if(level=="kabupaten"){
						admin.kabupaten._read();
					}else if(level=="kecamatan"){
						admin.kecamatan._read(admin.kabupatenS.id);
					}else{
						admin.korsad._read(admin.kecamatanS.id);
					}
				},
			});
		}
	},
	
	korsad : {
		
		_delete : function(id) {
			ajaxP.Request({
				url: UChost+''+admin.server+'c=korsad_delete&id='+id,
				success: function (result) {
					if(result.status) {
						admin.korsad._read(admin.kecamatanS.id);
					}
				},
			});
		}, 
		
		_update : function() {
			if(validatePassword(4)){
				ajaxP.Request({
					url: UChost+''+admin.server+'c=korsad_update',
					data: $('#admin-korsad-form-input').serialize(),
					success: function (result) {
						if(result.status) {
							$('#admin-korsad-popup-input').popup( 'close' );
							$('#admin-korsad-form-input')[0].reset();
							admin.korsad._read(admin.kecamatanS.id);
						} else {
							$('#admin-korsad-form-input input[name="nama"]').focus();
						}
					},
				});
			}
        },
		
		
		_kabupaten_select : function() {
			ajaxP.Request({
				url: UChost+''+admin.server+'c=kabupaten_select',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih Kabupaten -</option>'; 
					for (var i in result){
						for (var j in result[i]){
							baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
					$('#admin-kabupaten-select-2').html(baruHtml);
				}
			});      
		},
		
		_kecamatan_select : function(id_kabupaten) {
			ajaxP.Request({
				url: UChost+''+admin.server+'c=kecamatan_select&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status!=false){
						var baruHtml = '<option value="">- Pilih Kecamatan -</option>'; 
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('#admin-kecamatan-select-1').html(baruHtml);
						$('#admin-kecamatan-select-1').selectmenu('refresh');
						$('#admin-kecamatan-select-1').selectmenu('enable');
					}else{
						$('#admin-kecamatan-select-1').selectmenu('disable');
						$('#admin-kecamatan-select-1').selectmenu('refresh');
						$('#admin-korsad-table-div').hide();
					}
				}
			});      
		},
		
		_kelurahan_select : function(id_kecamatan) {
			ajaxP.Request({
				url: UChost+''+admin.server+'c=korsad_select&id_kecamatan='+id_kecamatan,
				success: function (result) {
					if(result.status!=false){
						var baruHtml = '<option value="">- Pilih Kelurahan -</option>'; 
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('#admin-kelurahan-select').html(baruHtml);
						$('#admin-kelurahan-select').selectmenu('enable');
					}else{
						$('#admin-korsad-table-div').hide();
					}
					$('#admin-kelurahan-select').selectmenu('refresh');
					
				}
			});      
		},
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: UChost+''+admin.server+'c=korsad_readUpdate&id='+id,
				success: function (result) {
					var baruHtml = '';
					for (var i in result){
						for (var j in result[i]){
							admin.korsad._pFU();
							$('#admin-korsad-form-input input[name="id"]').val(result[i][j].id);
							$('#admin-korsad-form-input input[name="nama"]').val(result[i][j].nama);
							$('#admin-korsad-form-input input[name="phone"]').val(result[i][j].phone);
							$('#admin-kelurahan-select').val(result[i][j].id_wilayah).selectmenu('refresh');
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
				},
			});                   
			
		},
		
		_read : function(id_kecamatan) {
			$('.gifloading').show();
			var baruHtml = ''; 
			ajaxP.Request({
				url: UChost+''+admin.server+'c=korsad_read&id_kecamatan='+id_kecamatan,
				success: function (result) {
					$('.gifloading').hide();
					//alert(JSON.stringify(result));
					if(result.status==false){
						$('#admin-korsad-table tbody').html(baruHtml);
						$('#admin-korsad-table').table( 'rebuild' );
						$('#admin-korsad-table-div').show();
						admin.korsad._kelurahan_select(id_kecamatan);
					} else {		
						var no=1;
						var arrayId = [];
						var arrayVal = [];
						for (var i in result){
							baruHtml += '<tr><td><select name="flip-7" id="flip-'+result[i]['Admin'].id+'" onchange="admin.aktivasi.satu(\''+result[i]['Admin'].id+'\',this.value)" class="slider" data-role="slider" data-mini="true">'+
								'<option value="0">Off</option><option value="1">On</option></select></td>'+
								'<th>'+no+'</th>'+
								'<td>'+result[i]['Kelurahan'].nama+'</td>'+
								'<td>'+result[i]['Admin'].nama+'</td>'+
								'<td>'+result[i]['Admin'].phone+'</td>'+
								'<td><a onclick="admin.korsad._readUpdate(\''+result[i]['Admin'].id+'\');" href="#admin-korsad-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a>'+
								'<a onclick="admin.korsad._pBD(\''+result[i]['Admin'].id+'\');" href="#admin-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+

							'</tr>'; no++;
							//alert(JSON.stringify(result[i]['Admin'].nama));
							arrayId.push('flip-'+result[i]['Admin'].id);
							arrayVal.push(result[i]['Admin'].aktif);
						}
						$('#admin-korsad-table tbody').html(baruHtml);
						$('#admin-korsad-table').table( 'rebuild' );
						$('#admin-korsad-table-div').show();
						admin.kecamatanS = {'id':id_kecamatan};
						admin.korsad._kelurahan_select(id_kecamatan);
						$('.slider').slider();
						
						if(result.status!=false){
							for(var j=0; j<arrayId.length; j++){
							$('#'+arrayId[j]).val(arrayVal[j]).trigger('create').slider("refresh");
							$('#'+arrayId[j]).trigger('slidestop');
							}
						}
					}
					
				}
			});
			
		},
		
		_input : function(){
			if(validatePassword(4)){
				ajaxP.Request({
					url: UChost+''+admin.server+'c=korsad_input&level='+admin.level.level,
					data: $('#admin-korsad-form-input').serialize(),
					success: function (result) {
						if(result.status) {	
							$('#admin-korsad-popup-input').popup( 'close' );
							$('#admin-korsad-form-input')[0].reset();
							admin.korsad._read(admin.kecamatanS.id);
						} else {
							$('#admin-korsad-form-input input[name="nama"]').focus();
						}
					}
				});
			}
		},
		
		_pFU : function(id){
			$('#admin-korsad-popup-input h3').html('Edit Data Admin korsad');
			$('#admin-korsad-form-input').attr('onsubmit','admin.korsad._update(); return false;');
		},
	
		_pBD : function(id){
			$('#admin-popup-delete').html('<a onclick="admin.korsad._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		},
		
		_pFI : function(){
			$('#admin-korsad-popup-input h3').html('Input Data Admin korsad');
			$('#admin-korsad-form-input')[0].reset();
			$('#admin-korsad-form-input').attr('onsubmit','admin.korsad._input(); return false;');
		}, 
	},
	
		
	tab : {
		_kecamatan : function(){
			admin.tab._closeall();
			$('#admin-kecamatan-tab').css('background','#FFD500');
			$('#admin-kecamatan').show();
			admin.level = {'level':'DPC','id_wilayah':''};
			admin.kabupatenS = {'id':window.localStorage.getItem("rcuserID_WIL")};
			admin.kecamatan._kabupaten_select();
			admin.kecamatan._read(admin.kabupatenS.id);
		},
		
		_korsad : function(){
			admin.tab._closeall();
			$('#admin-korsad-tab').css('background','#FFD500');
			$('#admin-korsad').show();
			admin.level = {'level':'korsak','id_wilayah':''};
			admin.korsad._read(admin.kecamatanS.id);
			//admin.korsad._kecamatan_select(admin.kabupatenS.id)
			//admin.kecamatan._read();
		},
		
		_closeall : function(){
			$('#admin-kecamatan').hide();
			$('#admin-korsad').hide();
			$('#admin-kecamatan-table-div').hide();
			$('#admin-korsad-table-div').hide();
			$('#admin-kecamatan-tab').css('background','#f9f9f9');
			$('#admin-korsad-tab').css('background','#f9f9f9');
			
		},
		
	},
};

var setting 	= {
	//server : 'http://localhost/myjob/ipna/_control/admin/admin.php?ajax&',
	//server : UChost+'_control/admin/admin.php?ajax&',
	
	url : {
		_change : function(){
			UChost = "http://"+$('#login-form-input input[name="new-url"]').val()+"/";
			window.localStorage.setItem("rcuserURL", UChost);
			alert('SERVER sekarang adalah '+UChost);
		},
		
		_default : function(){
			UChost = defUChost;
			window.localStorage.setItem("rcuserURL", UChost);
			alert('SERVER DEFAULT telah AKTIF');
		}

	},
	
	profil : {
		_update : function() {
			if(validatePassword(4)){
				ajaxP.Request({
					url: UChost+''+admin.server+'c=pusat_update',
					data: $('#setting-profil-form-input').serialize(),
					success: function (result) {
						if(result.status) {
							window.localStorage.setItem("rcuserID", $('#setting-profil-form-input input[name="id"]').val());
							window.localStorage.setItem("rcuserNAME", $('#setting-profil-form-input input[name="nama"]').val());
							window.localStorage.setItem("rcuserPASS", CryptoJS.MD5($('#setting-profil-form-input input[name="password"]').val()));
							$('.user-name').html(window.localStorage.getItem("rcuserNAME"));
							$('.ket-admin').html('admin '+window.localStorage.getItem("rcuserLEVEL"));
						}
					},
				});
			}
        },
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: UChost+''+admin.server+'c=pusat_readUpdate&id='+id,
				success: function (result) {
					var baruHtml = '';
					for (var i in result){
						for (var j in result[i]){
							$('#setting-profil-form-input input[name="id"]').val(result[i][j].id);
							$('#setting-profil-form-input input[name="nama"]').val(result[i][j].nama);
							$('#setting-profil-form-input input[name="phone"]').val(result[i][j].phone);
							
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
				},
			});                   
		},
	}
};

var rekap = {
	server : '?p=excel&ajax&',
	
	select : {
		kabupaten : function(){
			ajaxP.Request({
				url: UChost+''+admin.server+'c=kabupaten_select',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih Kabupaten -</option>'; 
					for (var i in result){
						for (var j in result[i]){
							baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
					$('#rekap-kabupaten-select-1').html(baruHtml);
					$('#rekap-kabupaten-select-2').html(baruHtml);
					$('#rekap-kabupaten-select-3').html(baruHtml);
					$('#rekap-kabupaten-select-4').html(baruHtml);
				}
			});      
		},
		
		kecamatan :function(id_kabupaten){
			ajaxP.Request({
				url: UChost+''+admin.server+'c=kecamatan_select&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status!=false){
						var baruHtml = '<option value="">- Pilih Kecamatan -</option>'; 
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('#rekap-kecamatan-select-1').html(baruHtml);
						$('#rekap-kecamatan-select-1').selectmenu('enable');
						$('#rekap-kecamatan-select-2').html(baruHtml);
						$('#rekap-kecamatan-select-2').selectmenu('enable');
					}
				}
			});      
		}
	},
	
	PemilihTetap : {
		model1 : function() {
			window.open(UChost+''+rekap.server+'token='+window.localStorage.getItem("rcuserPASS")+'&user='+window.localStorage.getItem("rcuserNAME")+'&ExportMode=toExcel&Rekap=PemilihTetap','_blank', 'location=yes');
		},
		
		model2 : function(form) {
			var id_kabupaten = $('#rekap-'+form+'-form-action select[name="id_kabupaten"]').val(); 
			window.open(UChost+''+rekap.server+'token='+window.localStorage.getItem("rcuserPASS")+'&user='+window.localStorage.getItem("rcuserNAME")+'&ExportMode=toExcel&Rekap=PemilihTetap&id_kabupaten='+id_kabupaten,'_blank', 'location=yes');
		},
		
		model3 : function(form) {
			var id_kecamatan = $('#rekap-'+form+'-form-action select[name="id_kecamatan"]').val(); 
			window.open(UChost+''+rekap.server+'token='+window.localStorage.getItem("rcuserPASS")+'&user='+window.localStorage.getItem("rcuserNAME")+'&ExportMode=toExcel&Rekap=PemilihTetap&id_kecamatan='+id_kecamatan,'_blank', 'location=yes');
		},
	},
	
	HitunganSuara : {
		model1 : function() {
			window.open(UChost+''+rekap.server+'token='+window.localStorage.getItem("rcuserPASS")+'&user='+window.localStorage.getItem("rcuserNAME")+'&ExportMode=toExcel&Rekap=HitunganSuara','_blank', 'location=yes');
		},
		
		model2 : function(form) {
			var id_kabupaten = $('#rekap-'+form+'-form-action select[name="id_kabupaten"]').val(); 
			window.open(UChost+''+rekap.server+'token='+window.localStorage.getItem("rcuserPASS")+'&user='+window.localStorage.getItem("rcuserNAME")+'&ExportMode=toExcel&Rekap=HitunganSuara&id_kabupaten='+id_kabupaten,'_blank', 'location=yes');
		},
		
		model3 : function(form) {
			var id_kecamatan = $('#rekap-'+form+'-form-action select[name="id_kecamatan"]').val(); 
			window.open(UChost+''+rekap.server+'token='+window.localStorage.getItem("rcuserPASS")+'&user='+window.localStorage.getItem("rcuserNAME")+'&ExportMode=toExcel&Rekap=HitunganSuara&id_kecamatan='+id_kecamatan,'_blank', 'location=yes');
		},
	}
}

var login 	= {
	//server : 'http://localhost/myjob/ipna/_control/admin/admin.php?ajax&',
	server : '_control/login.php?ajax&',
	
	form : {
		_input : function(){
			$('#login-form-error').html('');
			ajaxP.Request({
				url: UChost+''+login.server+'c=login_input',
				data: $('#login-form-input').serialize(),
				success: function (result) {
					if(result.status==false){
						//$('#login-form-input')[0].reset();
						$('#login-form-error').html('<div class="ui-body ui-icon-minus ui-body-a ui-corner-all" style="background:#FB7C51; color:#fff;">'+
							'<button class="ui-btn ui-icon-minus ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext">minus</button>Username atau Password anda salah.'+
							'</div>');
					} else {	
						//alert(JSON.stringify(result));
						
						window.localStorage.setItem("rcuserID", result['0']['Admin'].id);
						window.localStorage.setItem("rcuserNAME", result['0']['Admin'].nama);
						window.localStorage.setItem("rcuserLEVEL", result['0']['Admin'].level);
						window.localStorage.setItem("rcuserID_WIL", result['0']['Admin'].id_wilayah);
						window.localStorage.setItem("rcuserPASS", result['0']['Admin'].password);
						window.localStorage.setItem("rclogIN", 1);
				
						if(window.localStorage.getItem("rclogIN") == 1 && window.localStorage.getItem("rcuserPASS")==result['0']['Admin'].password) {
							if(window.localStorage.getItem("rcuserLEVEL")=="pusat"){
								window.location = "pusat.html";
							}else if(window.localStorage.getItem("rcuserLEVEL")=="DPD"){
								window.localStorage.setItem("rcuserNAMA_WIL", result['0']['Kabupaten'].nama);
								window.location = "DPD.html";
							}else if(window.localStorage.getItem("rcuserLEVEL")=="DPC"){
								window.localStorage.setItem("rcuserNAMA_WIL", result['0']['Kecamatan'].nama);
								window.location = "DPC.html";
							}else if(window.localStorage.getItem("rcuserLEVEL")=="korsak"){
								window.localStorage.setItem("rcuserNAMA_WIL", result['0']['Kelurahan'].nama);
								window.location = "korsak.html";
							}else{
								window.location = "index.html";
							}
						}
					} 
				}
			});
		},
		
		checkaktivasi : function(){
			ajaxP.Request({
				url: UChost+''+login.server+'c=login_check&username='+window.localStorage.getItem("rcuserNAME")+'&password='+window.localStorage.getItem("rcuserPASS"),
				success: function (result) {
					if(result.status == false){
						index.logout();
					}
				}
			});
		},
		
		_pFI : function(){
			$('#login-form-input')[0].reset();
			$('#login-form-input').attr('onsubmit','login.form._input(); return false;');
		}, 
	},
};

var ajaxP = (function () {
	
	function self() { }

	self.Request = function (params) {
		$.ajax({
			dataType: params.datatype || 'json',
			type: params.verb || 'post',
			//contentType: params.contentType || 'application/json',
			data: params.data || {},
			async: params.async || true,
			processData: params.processData || true,
			url: params.url || '',
			beforeSend: function() { $.mobile.loading('show'); },
			complete: function()   { $.mobile.loading('hide'); },
			error: function (request,error) { 
				//alert(JSON.stringify(request)); 
				alert('Network error has occurred please try again!'); 
				$('.no-connection').remove();
				$('.ui-content').append('<a href="#index" onclick="index.tab._sumbar();" class="ui-btn no-connection" style="background:#fff;"><img src="jquery-mobile/images/noconnect.png" /><br/> No Connection</a>');
				$('#index-sumbar').hide();
			},
			success: function (result, textStatus, xhr) {
				$('.no-connection').remove();
				if(result.status){
					alert(result.message);
					params.success(result);
				} else if(result.status==false) {
					alert('Warning! '+result.message);
					params.success(result);
				} else	{
					params.success(result);
				}
			}
		});

	};

	return self;

})($);



function validatePassword(id){
	var password = $('#password-'+id);
	var confirm_password = $('#confirm-password-'+id);
	if(password.val() != confirm_password.val()) {
		alert('Confirm password tidak sesuai');
		return false;
	}else {return true;}
}


//===========================================================================================================================================================	
	