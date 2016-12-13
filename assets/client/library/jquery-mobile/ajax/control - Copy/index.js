$(window).bind("orientationchange", function(){
    var orientation = window.orientation;
    var new_orientation = (orientation) ? 0 : 180 + orientation;
    $('body').css({
        "-webkit-transform": "rotate(" + new_orientation + "deg)"
    });
});

$( document ).ready(function() {
	if(window.localStorage.getItem("rclogIN") == 1) {
		ajaxP.Request({
			url: UChost+''+login.server+'c=login_check&username='+window.localStorage.getItem("rcuserNAME")+'&password='+window.localStorage.getItem("rcuserPASS"),
			success: function (result) {
				if(result.status == false){
					//index.logout();
				} else {
					$('.user-name').html(window.localStorage.getItem("rcuserNAME"));
					$('.ket-admin').html('admin '+window.localStorage.getItem("rcuserLEVEL"));
					var level = window.localStorage.getItem("rcuserLEVEL");
					window.location = level+".html";
					return false;
				}
			}
		});
	} 
	
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

if(window.localStorage.getItem("rclogIN") != 1) {
	$(document).on('pageinit', '#login', function() {
		panelswip('login');
		$("input[type=text]").focus(function(){
			$('#focus').focus();
		});
		$('input[type=text]:enabled:first').focus();
		//afteropen: function( event, ui ) {
            $('#focus').focus();
        //}
	});
	$(document).on('pageinit', '#about', function() {
		panelswip('about');
	});
	$(document).on('pageinit', '#index', function() {
		panelswip('index');
		index.tab._sumbar();
		//login.form.checkaktivasi();
	});
	$(document).on('pageinit', '#setting', function() {
		panelswip('setting');
		//setting.profil._readUpdate(window.localStorage.getItem("rcuserID"));	
	});
}
//===============================================================================================================================================================================================================	

var index 	= {
	server 		: '_control/admin/index.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/index.php?ajax&',
		
	kabupatenS : {},
	kecamatanS : {},
	kelurahanS : {},
	
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
			ajaxP.Request({
				url: UChost+''+index.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					index.tps._pFU();
					$('#index-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#index-tps-alamat').html('Alamat : '+result[0]['Tp'].alamat);
					$('#index-tps-pt').html('Pemilih Tetap : '+(parseFloat(result[0]['Tp'].pt_l)+parseFloat(result[0]['Tp'].pt_p)));
					$('#index-tps-pt_l').html('Pemilih Tetap L : '+parseFloat(result[0]['Tp'].pt_l));
					$('#index-tps-pt_p').html('Pemilih Tetap P : '+parseFloat(result[0]['Tp'].pt_p));
					$('#index-tps-tdk_sah').html('Suara yang tidak sah : '+result[0]['Tp'].tdk_sah);
					$('#index-tps-digunakan').html('Surat Suara Terpakai : '+result[0]['Tp'].digunakan);
					
					for (var i in result){
						$('#index-tps-form-input input[name="no-urut-'+result[i]['Hitung'].id_calon+'"]').val(result[i]['Hitung'].suara);
					}
					
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
		},
		
		_read : function(id_kelurahan) {
			
			$('#index-tps').html('');
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
													show: true, radius: 500,
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
								
								$('#index-tps').append('<div class="ui-corner-all ui-block-'+huruf+' custom-corners">'+
									'<a onclick="index.tps._readUpdate(\''+result[i]['Tp'].id+'\');" href="#index-tps-popup-input" data-transition="pop" data-position-to="window" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0; " data-rel="popup">'+
									'<p class="aside">TPS '+result[i]['Tp'].no+' </p>'+
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
										show: true, radius: 500,
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
			$('#index-kelurahan').html('');
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
													show: true, radius: 500,
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
										show: true, radius: 500,
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
			$('#index-kecamatan').html('');
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
													show: true, radius: 500,
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
								
								$('#index-kecamatan').append('<div class="ui-block-'+huruf+'">'+
									'<a href="#" onclick="index.tab._kelurahan('+result[i]['Kecamatan'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
									'<p class="aside">kec. '+result[i]['Kecamatan'].nama+'</p>'+
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
										show: true, radius: 500,
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
			$('#index-kabupaten').html('');
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
													show: true, radius: 500,
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
								
								$('#index-kabupaten').append('<div class="ui-block-'+huruf+'">'+
									'<a href="#" onclick="index.tab._kecamatan('+result[i]['Kabupaten'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
									'<p class="aside">'+result[i]['Kabupaten'].nama+'</p>'+
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
										show: true, radius: 500,
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
											show: true,	radius: 1,
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
	},
	
};

var statistik 	= {
	server 		: '_control/admin/index.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/index.php?ajax&',
		
	kabupatenS : {},
	kecamatanS : {},
	kelurahanS : {},
	
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
			statistik.tab._closeall();
			$('#statistik-sumbar').show();
			statistik.sumbar._init();
		},
		
		_kabupaten : function(){
			statistik.tab._closeall();
			$('#statistik-kabupaten').show();
			statistik.kabupaten._init();
		},
		
		_kecamatan : function(id_kabupaten){
			statistik.tab._closeall();
			$('#statistik-kecamatan').show();
			statistik.kecamatan._init(id_kabupaten);
		},
		
		_kelurahan : function(id_kecamatan){
			statistik.tab._closeall();
			$('#statistik-kelurahan').show();
			statistik.kelurahan._init(id_kecamatan);
		},
		
		_tps : function(id_kelurahan){
			statistik.tab._closeall();
			$('#statistik-tps').show();
			statistik.tps._init(id_kelurahan);
		},
		
		_closeall : function(){
			$('#statistik-sumbar').hide();
			$('#statistik-kabupaten').hide();
			$('#statistik-kecamatan').hide();
			$('#statistik-kelurahan').hide();
			$('#statistik-tps').hide();
		},
	},
	
	tps : {
		_init : function(id_kelurahan){
			statistik.tps._tab(id_kelurahan);
			statistik.tps._read(id_kelurahan);
		},

		_tab : function(id_kelurahan) {
			$('#statistik-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="statistik.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="statistik.tab._kabupaten();" class="ui-shadow ui-btn ui-icon-arrow-r ui-btn-icon-right ui-corner-all" >Kabupaten/Kota</a>'+
					'<a href="#" onclick="statistik.tab._kecamatan('+statistik.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+statistik.kabupatenS.nama+'</a>'+
					'<a href="#" onclick="statistik.tab._kelurahan('+statistik.kecamatanS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+statistik.kecamatanS.nama+'</a>'+
					'<select id="statistik-kelurahan-select" onchange="statistik.tab._tps(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#statistik-tab-control').trigger('create');
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=calon_read',
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
						$('#statistik-tps-table thead').html(thead);
						$('#statistik-tps-form-input-hitung').html(formhitung);
						$('#statistik-tps-form-input').trigger('create');
					}
				},
			});
			statistik.kelurahan._select(id_kelurahan);
		},
		
		_update : function() {
    
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=tps_update',
				data: $('#statistik-tps-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						$('#statistik-tps-popup-input').popup( 'close' );
						$('#statistik-tps-form-input')[0].reset();
						statistik.tps._read(statistik.kelurahanS.id);
					}
				},
			});                   
        },
		
		_readUpdate : function(id_tps) {
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					statistik.tps._pFU();
					$('#statistik-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#statistik-tps-alamat').html('Alamat : '+result[0]['Tp'].alamat);
					$('#statistik-tps-pt').html('Pemilih Tetap : '+(parseFloat(result[0]['Tp'].pt_l)+parseFloat(result[0]['Tp'].pt_p)));
					$('#statistik-tps-pt_l').html('Pemilih Tetap L : '+parseFloat(result[0]['Tp'].pt_l));
					$('#statistik-tps-pt_p').html('Pemilih Tetap P : '+parseFloat(result[0]['Tp'].pt_p));
					$('#statistik-tps-tdk_sah').html('Suara yang tidak sah : '+result[0]['Tp'].tdk_sah);
					$('#statistik-tps-digunakan').html('Surat Suara Terpakai : '+result[0]['Tp'].digunakan);
					
					for (var i in result){
						$('#statistik-tps-form-input input[name="no-urut-'+result[i]['Hitung'].id_calon+'"]').val(result[i]['Hitung'].suara);
					}
					
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
		},
		
		_read : function(id_kelurahan) {
			
			$('#statistik-tps').html('');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost +''+statistik.server+'c=tps_read&id_kelurahan='+id_kelurahan,
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_tps = 0;
						var no = 1;
						var data = [];
						$('#statistik-tps').html('');
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
													show: true, radius: 500,
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
								
								$('#statistik-tps').append('<div class="ui-corner-all ui-block-'+huruf+' custom-corners">'+
									'<a onclick="statistik.tps._readUpdate(\''+result[i]['Tp'].id+'\');" href="#statistik-tps-popup-input" data-transition="pop" data-position-to="window" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0; " data-rel="popup">'+
									'<p class="aside">TPS '+result[i]['Tp'].no+' </p>'+
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
										show: true, radius: 500,
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
				}
			});                 
		},
		
		_pFU : function(id){
			$('#statistik-tps-popup-input h3').html('Edit Data Pemungutan Suara');
			$('#statistik-tps-form-input').attr('onsubmit','statistik.tps._update(); return false;');
		},
	},
	
	kelurahan : {
		_init : function(id_kecamatan){
			statistik.kelurahan._tab(id_kecamatan);
			statistik.kelurahan._read(id_kecamatan);
		},

		_tab : function(id_kecamatan) {
			$('#statistik-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="statistik.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="statistik.tab._kabupaten();" class="ui-shadow ui-btn ui-icon-arrow-r ui-btn-icon-right ui-corner-all" >Kabupaten/Kota</a>'+
					'<a href="#" onclick="statistik.tab._kecamatan('+statistik.kabupatenS.id+')" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >'+statistik.kabupatenS.nama+'</a>'+
					'<select id="statistik-kecamatan-select" onchange="statistik.tab._kelurahan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#statistik-tab-control').trigger('create');
			statistik.kecamatan._select(id_kecamatan);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=kelurahan_select&id_kecamatan='+statistik.kecamatanS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kelurahan -</option>'; 
					for (var i in result){
						if(result[i]['Kelurahan'].id == selected){
							statistik.kelurahanS = {'id':result[i]['Kelurahan'].id,'nama':result[i]['Kelurahan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kelurahan'].id+'">'+result[i]['Kelurahan'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kelurahan'].nama));
					}
					$('#statistik-kelurahan-select').html(baruHtml);
					$('#statistik-kelurahan-select').val(selected).selectmenu('refresh');
				}
			});               
		},
		
		_read : function(id_kecamatan) {
			$('#statistik-kelurahan').html('');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=kelurahan_read&id_kecamatan='+id_kecamatan,
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_kelurahan = 0;
						var no = 1;
						var data = [];
						$('#statistik-kelurahan').html('');
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
													show: true, radius: 500,
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
								
								$('#statistik-kelurahan').append('<div class="ui-block-'+huruf+' ui-corner-all">'+
									'<a href="#" onclick="statistik.tab._tps('+result[i]['Kelurahan'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
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
										show: true, radius: 500,
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
				}
			});                 
		},
	},
	
	kecamatan : {
		_init : function(id_kabupaten){
			statistik.kecamatan._tab(id_kabupaten);
			statistik.kecamatan._read(id_kabupaten);
		},

		_tab : function(id_kabupaten) {
			$('#statistik-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="statistik.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="statistik.tab._kabupaten();" class="ui-shadow ui-icon-arrow-r ui-btn-icon-right ui-btn ui-corner-all" >Kabupaten/Kota</a>'+
					'<select id="statistik-kabupaten-select" onchange="statistik.tab._kecamatan(this.value)">'+
					'</select>'+
				'</fieldset>');
			$('#statistik-tab-control').trigger('create');
			statistik.kabupaten._select(id_kabupaten);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=kecamatan_select&id_kabupaten='+statistik.kabupatenS.id,
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kecamatan -</option>'; 
					for (var i in result){
						if(result[i]['Kecamatan'].id == selected){
							statistik.kecamatanS = {'id':result[i]['Kecamatan'].id,'nama':result[i]['Kecamatan'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kecamatan'].id+'">'+result[i]['Kecamatan'].nama+'</option>';
						//alert(JSON.stringify(result[i][Kecamatan].nama));
					}
					$('#statistik-kecamatan-select').html(baruHtml);
					$('#statistik-kecamatan-select').val(selected).selectmenu('refresh');
				}
			});
		},
		
		_read : function(id_kabupaten) {
			$('#statistik-kecamatan').html('');
			var baruHtml = '';
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_kecamatan = 0;
						var no = 1;
						var data = [];
						$('#statistik-kecamatan').html('');
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
													show: true, radius: 500,
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
								
								$('#statistik-kecamatan').append('<div class="ui-block-'+huruf+'">'+
									'<a href="#" onclick="statistik.tab._kelurahan('+result[i]['Kecamatan'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
									'<p class="aside">kec. '+result[i]['Kecamatan'].nama+'</p>'+
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
										show: true, radius: 500,
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
				}
			});              
		},
		
	},
	
	kabupaten : {
		_init : function(){
			statistik.kabupaten._tab();
			statistik.kabupaten._read();
		},
		
		_tab : function() {
			$('#statistik-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#" onclick="statistik.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all ui-icon-arrow-r ui-btn-icon-right" >Sumatera Barat</a>'+
					'<a href="#" onclick="statistik.tab._kabupaten();" class="ui-shadow ui-btn ui-corner-all" >Kabupaten/Kota</a>'+
				'</fieldset>');
			$('#statistik-tab-control').trigger('create');
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=kabupaten_select',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kabupaten -</option>'; 
					for (var i in result){
						if(result[i]['Kabupaten'].id == selected){
							statistik.kabupatenS = {'id':result[i]['Kabupaten'].id,'nama':result[i]['Kabupaten'].nama};
						}
						baruHtml += '<option value="'+result[i]['Kabupaten'].id+'">'+result[i]['Kabupaten'].nama+'</option>';
						//alert(JSON.stringify(result[i]['Kabupaten'].nama));
					}
					$('#statistik-kabupaten-select').html(baruHtml);
					$('#statistik-kabupaten-select').val(selected).selectmenu('refresh');
				}
			});      
		},
		
		_read : function() {
			$('#statistik-kabupaten').html('');
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=kabupaten_read',
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result));
						var id_kabupaten = 0;
						var no = 1;
						var data = [];
						$('#statistik-kabupaten').html('');
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
													show: true, radius: 500,
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
								
								$('#statistik-kabupaten').append('<div class="ui-block-'+huruf+'">'+
									'<a href="#" onclick="statistik.tab._kecamatan('+result[i]['Kabupaten'].id+');" class="ui-shadow ui-btn ui-corner-all" style="padding:0; margin-left:0;">'+
									'<p class="aside">'+result[i]['Kabupaten'].nama+'</p>'+
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
										show: true, radius: 500,
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
			
		},
		
	},
	
	sumbar : {
		_init : function(){
			statistik.sumbar._tab();
			statistik.sumbar._read();
			
		},
		
		_tab : function() {
			$('#statistik-tab-control').html(
				'<fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">'+
					'<a href="#statistik-kabupaten" onclick="statistik.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all" >Sumatera Barat</a>'+
				'</fieldset>');
			$('#statistik-tab-control').trigger('create');
		},
		
		_read : function() {
			
			ajaxP.Request({
				url: UChost+''+statistik.server+'c=sumbar_read',  
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
						$('#statistik-graph1').html('');
						//alert(JSON.stringify(data));
						if(result[i][''].suara == 0){
							$('#statistik-graph1').html('<p><br/><br/><br/>Suara Masih Null</p>');
						} else { 
							$.plot($('#statistik-graph1'), data, {
								series: {
									pie: { 
										show: true, radius: 1, 
										label: {
											show: true,	radius: 1,
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
	},
	
};

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
					//alert(JSON.stringify(result));
					if(result.status==false){
						//$('#login-form-input')[0].reset();
						$('#login-form-error').html('<div class="ui-body ui-icon-minus ui-body-a ui-corner-all" style="background:#FB7C51; color:#fff;">'+
							'<button class="ui-btn ui-icon-minus ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext">minus</button>Username atau Password anda salah.'+
							'</div>');
						alert(JSON.stringify(result));
					} else {	
						alert(JSON.stringify(result));
						
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

var setting = {
	//server : 'http://localhost/myjob/ipna/_control/admin/admin.php?ajax&',
	//server : UChost+'_control/admin/admin.php?ajax&',
	
	url : {
		_change : function(){
			UChost = "http://"+$('#login-form-input input[name="new-url"]').val()+"/";
			alert('SERVER sekarang adalah '+UChost);
		},

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
				$('.ui-content').append('<a class="ui-btn no-connection" style="background:#fff;"><img src="jquery-mobile/images/noconnect.png" /><br/> No Connection</a>');
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
	