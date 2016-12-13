$(window).bind("orientationchange", function(){
    var orientation = window.orientation;
    var new_orientation = (orientation) ? 0 : 180 + orientation;
    $('body').css({
        "-webkit-transform": "rotate(" + new_orientation + "deg)"
    });
});

//======================================================================JAVASCRIPT===============================================================================================================================
$(document).on('pageinit', '#wilayah', function() {
    wilayah.tab._kabupaten();
});
$(document).on('pageinit', '#tpsPage', function() {
    tpsPage.tab._kabupaten();
});
$(document).on('pageinit', '#report', function() {
	report.kesalahan._read();
	report.tps._init();
});
$(document).on('pageinit', '#index', function() {
    index.tab._sumbar();
});
$(document).on('pageinit', '#hitung', function() {
    hitung.tab._kabupaten();
});
$(document).on('pageinit', '#calon', function() {
    calon.calon._read();
});
$(document).on('pageinit', '#admin', function() {
    admin.tab._pusat();
});
//===============================================================================================================================================================================================================	

var wilayah = {
	//server : 'http://localhost/myjob/ipna/_control/admin/wilayah.php?ajax&',
	server : host+'_control/admin/wilayah.php?ajax&',
	kabupatenS : {},
	
	kelurahan : {
		
		_delete : function(id) {
			ajaxP.Request({
				url: wilayah.server+'c=kelurahan_delete&id='+id,
				success: function (result) {
					if(result.status) {
						wilayah.kelurahan._read(wilayah.kabupatenS.id);
					} else {
						alert('gagal '+result.message); 
					}
				},
			});
		}, 
		
		_update : function() {
    
			ajaxP.Request({
				url: wilayah.server+'c=kelurahan_update',
				data: $('#wilayah-kelurahan-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						var Otext = $('#wilayah-kelurahan-form-input input[name="nama_lama"]').val();
						var Ntext = $('#wilayah-kelurahan-form-input input[name="nama"]').val();
						$('td:contains("'+Otext+'")').html(Ntext);
						$('#wilayah-kelurahan-popup-input').popup( 'close' );
						$('#wilayah-kelurahan-form-input')[0].reset();
						//wilayah.kelurahan._read();
					} else {
						alert(result.message); 
					}
				},
			});                   
			
        },
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: wilayah.server+'c=kelurahan_readUpdate&id='+id,
				success: function (result) {
					if(!result){
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = '';
						for (var i in result){
							wilayah.kelurahan._pFU();
							$('#wilayah-kelurahan-form-input input[name="id"]').val(result[i]['Kelurahan'].id);
							$('#wilayah-kelurahan-form-input input[name="nama"]').val(result[i]['Kelurahan'].nama);
							$('#wilayah-kelurahan-form-input input[name="nama_lama"]').val(result[i]['Kelurahan'].nama);
							//$('#wilayah-select').val(myval).selectmenu('refresh');
							$('#wilayah-kelurahan-form-input select[name="id_kabupaten"]').val(result[i]['Kecamatan'].id_kabupaten).selectmenu('refresh');
							$('#wilayah-kelurahan-form-input select[name="id_kecamatan"]').val(result[i]['Kelurahan'].id_kecamatan).selectmenu('refresh');
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
				},
				
			});                   
			
		},
		
		_read : function(id_kabupaten) {
			ajaxP.Request({
				url: wilayah.server+'c=kelurahan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(!result){
						$('#wilayah-readkelurahan').html('');
						alert('Warning! Data Kosong | Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var fixHtml = '';
						var baruHtml = ''; var no=1;
						var kecamatanId = 0; var filtertext = '';
						for (var i in result){
							filtertext += result[i]['Kecamatan'].nama+' '+result[i]['Kelurahan'].nama+' ';
							baruHtml += '<li data-filtertext="'+result[i]['Kecamatan'].nama+' '+result[i]['Kelurahan'].nama+'">'+
										'<table><tr>'+
											'<td>'+no+'. </td>'+
											'<td width="100%">'+result[i]['Kelurahan'].nama+'</td>'+
											'<td><a onclick="wilayah.kelurahan._readUpdate(\''+result[i]['Kelurahan'].id+'\');" href="#wilayah-kelurahan-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a></td>'+
											'<td><a onclick="wilayah.kelurahan._pBD(\''+result[i]['Kelurahan'].id+'\');" href="#wilayah-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+
										'</tr></table>'+
									'</li>'; no++;
							var headHtml = '<div data-role="collapsible" id="wilayah-kecamatan-nama-'+result[i]['Kecamatan'].nama+'" data-filtertext="'+filtertext+'">'+
								'<h3>'+result[i]['Kecamatan'].nama+'</h3>'+
								'<ul data-role="listview" data-inset="false">'+baruHtml+'</ul></div>';
		
							if(parseFloat(i) < ((result.length)-1) ){
								if(result[parseFloat(i)+1]['Kecamatan'].id != result[i]['Kecamatan'].id ) {
									fixHtml += headHtml;
									baruHtml = '';
									filtertext = ''; no=1;
								}
							} else if (parseFloat(i) == ((result.length)-1)) {
								fixHtml += headHtml;
							}
							
							if((parseFloat(i)+1) < ((result.length)-1)){	
								kecamatanId = result[parseFloat(i)+1]['Kecamatan'].id;
							}else {
								kecamatanId = 0;
							}
							
						}
						$('#wilayah-readkelurahan').html(fixHtml);
						$('#wilayah-readkelurahan').collapsibleset().trigger('create');
						wilayah.kabupatenS = {'id':id_kabupaten};
						$('#wilayah-kabupaten-select-2').val(wilayah.kabupatenS.id).selectmenu('refresh');	
						
					}
				},
			});                   
			
		},
		
		_input : function() {
    
			ajaxP.Request({
				url: wilayah.server+'c=kelurahan_input',
				data: $('#wilayah-kelurahan-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						$('#wilayah-kelurahan-popup-input').popup( 'close' );
						wilayah.kelurahan._read($('#wilayah-kelurahan-form-input select[name="id_kabupaten"]').val());
						$('#wilayah-kelurahan-form-input')[0].reset();
						
					} else {
					}
				},
			});                   
			
        },
		
		_pFI : function(){
			$('#wilayah-kelurahan-popup-input h3').html('Input Data kelurahan');
			$('#wilayah-kelurahan-form-input')[0].reset();
			$('#wilayah-kelurahan-form-input select[name="id_kabupaten"]').val(wilayah.kabupatenS.id);
			$('#wilayah-kelurahan-form-input').attr('onsubmit','wilayah.kelurahan._input(); return false;');
		}, 
		
		_pFU : function(id){
			$('#wilayah-kelurahan-popup-input h3').html('Edit Data kelurahan');
			$('#wilayah-kelurahan-form-input').attr('onsubmit','wilayah.kelurahan._update(); return false;');
		}, 
		
		_pBD : function(id){
			$('#wilayah-popup-delete').html('<a onclick="wilayah.kelurahan._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		}
			
	},
	
	kecamatan : {
		
		_delete : function(id) {
			ajaxP.Request({
				url: wilayah.server+'c=kecamatan_delete&id='+id,
				success: function (result) {
					if(result.status) {
						
						wilayah.kecamatan._read();
					} else {
						alert('gagal '+result.message); 
					}
				},
			});
		}, 
		
		_update : function() {
    
			ajaxP.Request({
				url: wilayah.server+'c=kecamatan_update',
				data: $('#wilayah-kecamatan-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						
						$('#wilayah-kecamatan-popup-input').popup( 'close' );
						$('#wilayah-kecamatan-form-input')[0].reset();
						wilayah.kecamatan._read();
					} else {
						 
					}
				},
			});                   
			
        },
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: wilayah.server+'c=kecamatan_readUpdate&id='+id,
				success: function (result) {
					if(!result){
						alert('Warning! Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = '';
						for (var i in result){
							for (var j in result[i]){
								wilayah.kecamatan._pFU();
								$('#wilayah-kecamatan-form-input input[name="id"]').val(result[i][j].id);
								$('#wilayah-kecamatan-form-input input[name="nama"]').val(result[i][j].nama);
								//$('#wilayah-select').val(myval).selectmenu('refresh');
								$('#wilayah-kecamatan-form-input select[name="id_kabupaten"]').val(result[i][j].id_kabupaten).selectmenu('refresh');
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
					}
				},
			});                   
			
		},
		
		_read : function() {
			ajaxP.Request({
				url: wilayah.server+'c=kecamatan_read',
				success: function (result) {
					if(!result){
						alert('Warning! Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var fixHtml = '';
						var baruHtml = ''; var no=1;
						var kabupatenId = 0; var filtertext = '';
					for (var i in result){
							filtertext += result[i]['Kabupaten'].nama+' '+result[i]['Kecamatan'].nama+' ';
							baruHtml += '<li data-filtertext="'+result[i]['Kabupaten'].nama+' '+result[i]['Kecamatan'].nama+'">'+
										'<table><tr>'+
											'<td>'+no+'. </td>'+
											'<td width="100%">'+result[i]['Kecamatan'].nama+'</td>'+
											'<td><a onclick="wilayah.kecamatan._readUpdate(\''+result[i]['Kecamatan'].id+'\');" href="#wilayah-kecamatan-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a></td>'+
											'<td><a onclick="wilayah.kecamatan._pBD(\''+result[i]['Kecamatan'].id+'\');" href="#wilayah-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+
										'</tr></table>'+
									'</li>'; no++;
							var headHtml = '<div data-role="collapsible" id="wilayah-kabupaten-nama-'+result[i]['Kabupaten'].nama+'" data-filtertext="'+filtertext+'">'+
								'<h3>'+result[i]['Kabupaten'].nama+'</h3>'+
								'<ul data-role="listview" data-inset="false">'+baruHtml+'</ul></div>';
		
							if(parseFloat(i) < ((result.length)-1) ){
								if(result[parseFloat(i)+1]['Kabupaten'].id != result[i]['Kabupaten'].id ) {
									fixHtml += headHtml;
									baruHtml = '';
									filtertext = ''; no=1;
								}
							} else if (parseFloat(i) == ((result.length)-1)) {
								fixHtml += headHtml;
							}
							
							if((parseFloat(i)+1) < ((result.length)-1)){	
								kabupatenId = result[parseFloat(i)+1]['Kabupaten'].id;
							}else {
								kabupatenId = 0;
							}
							
						}
						$('#wilayah-readkecamatan').html(fixHtml);
						$('#wilayah-readkecamatan').collapsibleset().trigger('create');
					}
				},
			});                   
			
		},
		
		_input : function() {
    
			ajaxP.Request({
				url: wilayah.server+'c=kecamatan_input',
				data: $('#wilayah-kecamatan-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						
						$('#wilayah-kecamatan-popup-input').popup( 'close' );
						$('#wilayah-kecamatan-form-input')[0].reset();
						wilayah.kecamatan._read();
					} else {
						 
					}
				},
			});                   
			
        },
		
		_pFI : function(){
			$('#wilayah-kecamatan-popup-input h3').html('Input Data kecamatan');
			$('#wilayah-kecamatan-form-input')[0].reset();
			$('#wilayah-kecamatan-form-input').attr('onsubmit','wilayah.kecamatan._input(); return false;');
		}, 
		
		_pFU : function(id){
			$('#wilayah-kecamatan-popup-input h3').html('Edit Data kecamatan');
			$('#wilayah-kecamatan-form-input').attr('onsubmit','wilayah.kecamatan._update(); return false;');
		}, 
		
		_pBD : function(id){
			$('#wilayah-popup-delete').html('<a onclick="wilayah.kecamatan._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		}
			
	},
	
	kabupaten : {
		
		_delete : function(id) {
			ajaxP.Request({
				url: wilayah.server+'c=kabupaten_delete&id='+id,
				success: function (result) {
					if(result.status) {
							
						wilayah.kabupaten._read();
					} else {
						
					}
				},
			});
		}, 
		
		_update : function() {
    
			ajaxP.Request({
				url: wilayah.server+'c=kabupaten_update',
				data: $('#wilayah-kabupaten-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						
						$('#wilayah-kabupaten-popup-input').popup( 'close' );
						$('#wilayah-kabupaten-form-input')[0].reset();
						wilayah.kabupaten._read();
					} else {
						 
					}
				},
			});                   
			
        },
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: wilayah.server+'c=kabupaten_readUpdate&id='+id,
				success: function (result) {
					if(!result){
						alert('Warning! Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = '';
						for (var i in result){
							for (var j in result[i]){
								wilayah.kabupaten._pFU();
								$('#wilayah-kabupaten-form-input input[name="id"]').val(result[i][j].id);
								$('#wilayah-kabupaten-form-input input[name="nama"]').val(result[i][j].nama);
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
					}
				},
			});                   
			
		},
		
		_read : function() {
			ajaxP.Request({
				url: wilayah.server+'c=kabupaten_read',
				success: function (result) {
					if(!result){
						alert('Warning! Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = ''; var no=1;
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<li data-filtertext="NASDAQ:'+result[i][j].nama+'">'+
									'<table style="clear:both;"><tr>'+
										'<td>'+no+'. </td>'+
										'<td width="100%">'+result[i][j].nama+'</td>'+
										'<td><a onclick="wilayah.kabupaten._readUpdate(\''+result[i][j].id+'\');" href="#wilayah-kabupaten-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a></td>'+
										'<td><a onclick="wilayah.kabupaten._pBD(\''+result[i][j].id+'\');" href="#wilayah-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+
									'</tr></table>'+
								'</li>';no++;
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('#wilayah-readkabupaten').html(baruHtml);
						$('#wilayah-readkabupaten').listview('refresh');
					}
				},
			});                   
			
		},
		
		_input : function() {
    
			ajaxP.Request({
				url: wilayah.server+'c=kabupaten_input',
				data: $('#wilayah-kabupaten-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						
						$('#wilayah-kabupaten-popup-input').popup( 'close' );
						$('#wilayah-kabupaten-form-input')[0].reset();
						wilayah.kabupaten._read();
					} else {
						
					}
				},
			});                   
			
        },
		
		_pFI : function(){
			$('#wilayah-kabupaten-popup-input h3').html('Input Data kabupaten');
			$('#wilayah-kabupaten-form-input')[0].reset();
			$('#wilayah-kabupaten-form-input').attr('onsubmit','wilayah.kabupaten._input(); return false;');
		}, 
		
		_pFU : function(id){
			$('#wilayah-kabupaten-popup-input h3').html('Edit Data kabupaten');
			$('#wilayah-kabupaten-form-input').attr('onsubmit','wilayah.kabupaten._update(); return false;');
		}, 
		
		_pBD : function(id){
			$('#wilayah-popup-delete').html('<a onclick="wilayah.kabupaten._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		}
			
	},
		
	tab : {
			
		_kabupaten : function(){
			wilayah.tab._closeall();
			$('#wilayah-kabupaten-tab').css('background','#FFD500');
			$('#wilayah-kabupaten').show();
			wilayah.kabupaten._read();
		},
		
		_kecamatan : function(){
			wilayah.tab._closeall();
			wilayah.tab._kabupaten_select();
			$('#wilayah-kecamatan-tab').css('background','#FFD500');
			$('#wilayah-kecamatan').show();
			wilayah.kecamatan._read();
		},
		
		_kelurahan : function(){
			wilayah.tab._closeall();
			wilayah.tab._kabupaten_select();
			wilayah.tab._kecamatan_select();
			$('#wilayah-kelurahan-tab').css('background','#FFD500');
			$('#wilayah-readkelurahan').html('');
			$('#wilayah-kelurahan').show();
		},
		
		_closeall : function(){
			$('#wilayah-kabupaten').hide();
			$('#wilayah-kecamatan').hide();
			$('#wilayah-kelurahan').hide();
			$('#wilayah-kabupaten-tab').css('background','#f9f9f9');
			$('#wilayah-kecamatan-tab').css('background','#f9f9f9');
			$('#wilayah-kelurahan-tab').css('background','#f9f9f9');
			
		},
		
		_kabupaten_select : function() {
			ajaxP.Request({
				url: wilayah.server+'c=kabupaten_read',
				success: function (result) {
					if(!result){
						alert('Warning! Data Kosong | Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = '<option value="">- Pilih kabupaten -</option>'; 
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('.kabupaten-select').html(baruHtml);
						$('#wilayah-kabupaten-select-2').selectmenu('refresh');
					}
				},
			});                   
			
		},
		
		_kecamatan_select : function(id_kabupaten) {
			ajaxP.Request({
				url: wilayah.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(!result){
						alert('Warning! Data Kosong ! Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = '<option value="">- Pilih kecamatan -</option>'; 
						for (var i in result){
							baruHtml += '<option value="'+result[i]['Kecamatan'].id+'">'+result[i]['Kecamatan'].nama+'</option>';
							//alert(JSON.stringify(result[i][j].nama));
						}
						$('#wilayah-kecamatan-select').html(baruHtml);
							
					}
				},
			});                   
			
		},
	},
};

var tpsPage = {

	server : host+'_control/admin/tps.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/tps.php?ajax&',
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
				url: tpsPage.server+'c=tps_delete&id='+id,
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
				url: tpsPage.server+'c=tps_update',
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
				url: tpsPage.server+'c=tps_readUpdate&id='+id,
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
			var baruHtml = '';
			ajaxP.Request({
				url: tpsPage.server+'c=tps_read&id_kelurahan='+id_kelurahan,
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
										'<td>'+result[i][j].alamat+'</td>'+
										'<td>'+result[i][j].pt_l+'</td>'+
										'<td>'+result[i][j].pt_p+'</td>'+
										'<td>'+(parseFloat(result[i][j].pt_l)+parseFloat(result[i][j].pt_p))+'</td>'+
										'<td><a onclick="tpsPage.tps._readUpdate(\''+result[i][j].id+'\');" href="#tpsPage-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a>'+
										'<a onclick="tpsPage.tps._pBD(\''+result[i][j].id+'\');" href="#tpsPage-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+
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
				url: tpsPage.server+'c=tps_input&id_kelurahan='+tpsPage.kelurahanS.id,
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
				url: tpsPage.server+'c=kelurahan_read&id_kecamatan='+tpsPage.kecamatanS.id,
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
			var baruHtml = '';
			ajaxP.Request({
				url: tpsPage.server+'c=kelurahan_read&id_kecamatan='+id_kecamatan,
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
				url: tpsPage.server+'c=kelurahan_input&id_kecamatan='+tpsPage.kecamatanS.id,
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
				url: tpsPage.server+'c=kecamatan_read&id_kabupaten='+tpsPage.kabupatenS.id,
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
			var baruHtml = '';
			ajaxP.Request({
				url: tpsPage.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status==false){
						$('#tpsPage-kecamatan-table tbody').html('');
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
				url: tpsPage.server+'c=kecamatan_input&id_kabupaten='+tpsPage.kabupatenS.id,
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
				url: tpsPage.server+'c=kabupaten_read',
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
			
			ajaxP.Request({
				url: tpsPage.server+'c=kabupaten_read',
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
				url: tpsPage.server+'c=kabupaten_input',
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
	server 		: host+'_control/admin/report.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/report.php?ajax&',
		
	kesalahan : {
		
		_read : function() {
			var baruHtml = '';
			ajaxP.Request({
				url: report.server+'c=kesalahan_read',
				success: function (result) {
					if(result.status==false){
						
					} else {
						//alert(JSON.stringify(result)); 
						for (var i in result){
							baruHtml += '<li>Pemilih Tetap : '+result[i]['Tbl'].p_tetap+' | Suara Masuk : '+result[i]['Tbl'].suara_masuk+
							' pada <a href="#report-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" onclick="report.tps._readUpdate(\''+result[i]['Tbl'].id+'\');" href="#"> TPS '+
							result[i]['Tbl'].no+' (Alamat : '+result[i]['Tbl'].alamat+', Kelurahan '+result[i]['Kelurahan'].nama+', Kecamatan '+
							result[i]['Kecamatan'].nama+', Kabupaten/Kota '+result[i]['Kabupaten'].nama+')</a></li>';
							//alert(JSON.stringify(result[i][j].nama));
							
						}
						$('#report-daftar-tps-perhitungan-salah').html(baruHtml);
						
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
				url: report.server+'c=calon_read',
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
					}
				},
			});
			
		},
		
		_update : function() {
    
			ajaxP.Request({
				url: report.server+'c=tps_update',
				data: $('#report-tps-form-input').serialize(),
				success: function (result) {
					if(result.status) {
						$('#report-tps-popup-input').popup( 'close' );
						$('#report-tps-form-input')[0].reset();
						report.kesalahan._read();
					}
				},
			});                   
        },
		
		_readUpdate : function(id_tps) {
			ajaxP.Request({
				url: report.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					report.tps._pFU();
					$('#report-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#report-tps-form-input input[name="tdk_sah"]').val(result[0]['Tp'].tdk_sah);
					
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
	server 		: host+'_control/admin/index.php?ajax&',
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
		window.location = "login.html#login";
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
				url: index.server+'c=calon_read',
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
				url: index.server+'c=tps_update',
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
				url: index.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					index.tps._pFU();
					$('#index-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#index-tps-alamat').html('Alamat : '+result[0]['Tp'].alamat);
					$('#index-tps-pt').html('Pemilih Tetap : '+(parseFloat(result[0]['Tp'].pt_l)+parseFloat(result[0]['Tp'].pt_p)));
					$('#index-tps-form-input input[name="tdk_sah"]').val(result[0]['Tp'].tdk_sah);
					
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
			var baruHtml = '';
			ajaxP.Request({
				url: index.server+'c=tps_read&id_kelurahan='+id_kelurahan,
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
													show: true, radius: 1,
													label: {
														show: true,	radius: 2/3,
														formatter: function(label, series){
															return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:30px; border-radius:15px;">'+
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
									'<div id="'+result[i]['Tp'].id+'-tps-graph" class="graph-2" style="height:150px;" ></div></a></div>');
								
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
										show: true, radius: 1,
										label: {
											show: true,	radius: 2/3,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:30px; border-radius:15px;">'+
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
				url: index.server+'c=kelurahan_select&id_kecamatan='+index.kecamatanS.id,
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
			var baruHtml = '';
			ajaxP.Request({
				url: index.server+'c=kelurahan_read&id_kecamatan='+id_kecamatan,
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
													show: true, radius: 1,
													label: {
														show: true,	radius: 2/3,
														formatter: function(label, series){
															return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
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
										show: true, radius: 1,
										label: {
											show: true,	radius: 2/3,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
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
				url: index.server+'c=kecamatan_select&id_kabupaten='+index.kabupatenS.id,
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
			var baruHtml = '';
			ajaxP.Request({
				url: index.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
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
													show: true, radius: 1,
													label: {
														show: true,	radius: 2/3,
														formatter: function(label, series){
															return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
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
										show: true, radius: 1,
										label: {
											show: true,	radius: 2/3,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
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
				url: index.server+'c=kabupaten_select',
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
			ajaxP.Request({
				url: index.server+'c=kabupaten_read',
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
													show: true, radius: 1,
													label: {
														show: true,	radius: 2/3,
														formatter: function(label, series){
															return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
															'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
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
										show: true, radius: 1,
										label: {
											show: true,	radius: 2/3,
											formatter: function(label, series){
												return '<div style="font-size:8pt;text-align:center;padding:0 0 3px;color:#000;">'+
												'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:50px; border-radius:25px;">'+
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
					'<a href="#" onclick="index.tab._sumbar();" class="ui-shadow ui-btn ui-corner-all" >Sumatera Barat</a>'+
				'</fieldset>');
			$('#index-tab-control').trigger('create');
		},
		
		_read : function() {
			
			ajaxP.Request({
				url: index.server+'c=sumbar_read',  
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
												'<img src="'+host+'_view/file/images/calon/'+label['Calon'].gambar+'" style="width:80px; border-radius:7px;">'+
												'<br/>'+label['Calon'].alias+'<br/>'+(parseFloat(label[''].suara)/parseFloat(label[''].sah)*100).toFixed(2)+'%<br/>'+label[''].suara+'</div>';
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

var hitung 	= {
	server 		: host+'_control/admin/hitung.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/hitung.php?ajax&',
	kabupatenS  : {},
	kecamatanS 	: {},
	kelurahanS	: {},
		
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
				url: hitung.server+'c=calon_read',
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
				url: hitung.server+'c=tps_update',
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
			ajaxP.Request({
				url: hitung.server+'c=tps_readUpdate&id_tps='+id_tps,
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = '';
					hitung.tps._pFU();
					$('#hitung-tps-form-input input[name="id_tps"]').val(result[0]['Tp'].id);
					$('#hitung-tps-form-input input[name="tdk_sah"]').val(result[0]['Tp'].tdk_sah);
					
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
			var baruHtml = '';
			ajaxP.Request({
				url: hitung.server+'c=tps_read&id_kelurahan='+id_kelurahan,
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
									baruHtml += '<td><a onclick="hitung.tps._readUpdate(\''+result[i-1]['Tp'].id+'\');" href="#hitung-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-grid ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a></td></tr>';					
								}
								baruHtml += '<tr>'+
								'<th><a onclick="hitung.tps._readUpdate(\''+result[i]['Tp'].id+'\');" href="#hitung-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup">'+result[i]['Tp'].no+'</a></th>'+
								'<th><a onclick="hitung.tps._readUpdate(\''+result[i]['Tp'].id+'\');" href="#hitung-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup">'+result[i]['Tp'].alamat+'</a></th>'+
								'<td>'+(parseFloat(result[i]['Tp'].pt_l)+parseFloat(result[i]['Tp'].pt_p))+'</td>'+
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
						baruHtml += '<td><a onclick="hitung.tps._readUpdate(\''+result[i]['Tp'].id+'\');" href="#hitung-tps-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-grid ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a></td></tr>';					
								
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
				url: hitung.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>No</th><th data-priority="7">Kelurahan</th><th data-priority="2">Pemilih Tetap</th><th data-priority="5">Suara Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="4">Suara Sah</th><th data-priority="3">Golput</th></tr>';
						$('#hitung-kelurahan-table thead').html(thead);
						$('#hitung-kelurahan-form-input-hitung').html(formhitung);
					}
				},
			});
			hitung.kecamatan._select(id_kecamatan);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: hitung.server+'c=kelurahan_select&id_kecamatan='+hitung.kecamatanS.id,
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
			var baruHtml = '';
			ajaxP.Request({
				url: hitung.server+'c=kelurahan_read&id_kecamatan='+id_kecamatan,
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
										baruHtml += '<td colspan="3"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td></tr>';
									} else {
										baruHtml += '<td>'+result[i-1][''].sah+'</td><td>'+(golput-parseFloat(result[i-1][''].sah))+'</td></tr>';
									}
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
							baruHtml += '<td colspan="3"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td></tr>';
						} else {
							baruHtml += '<td>'+result[i][''].sah+'</td><td>'+(golput-parseFloat(result[i][''].sah))+'</td></tr>';
						}
					
						$('#hitung-kelurahan-table tbody').html(baruHtml);
						$('#hitung-kelurahan-table').table( 'rebuild' );
						
					}
				}
			});                 
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: hitung.server+'c=kelurahan_input&id_kecamatan='+hitung.kecamatanS.id,
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
				url: hitung.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>No</th><th data-priority="7">Kecamatan</th><th data-priority="2">Pemilih Tetap</th><th data-priority="5">Suara Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="4">Suara Sah</th><th data-priority="3">Golput</th></tr>';
						$('#hitung-kecamatan-table thead').html(thead);
						$('#hitung-kecamatan-form-input-hitung').html(formhitung);
					}
				},
			});
			hitung.kabupaten._select(id_kabupaten);
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: hitung.server+'c=kecamatan_select&id_kabupaten='+hitung.kabupatenS.id,
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
			var baruHtml = '';
			ajaxP.Request({
				url: hitung.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status==false){
						$('#hitung-kecamatan-table tbody').html('');
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
										baruHtml += '<td colspan="3"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td></tr>';
									} else {
										baruHtml += '<td>'+result[i-1][''].sah+'</td><td>'+(golput-parseFloat(result[i-1][''].sah))+'</td></tr>';
									}
									no++;					
								}
								baruHtml += '<tr>'+
								'<th>'+no+'</th>'+
								'<th><a href="#" onclick="hitung.tab._kelurahan(\''+result[i]['Kecamatan'].id+'\')">'+result[i]['Kecamatan'].nama+'</a></th>'+
								'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
								'<td>'+result[i][''].tdk_sah+'</td>';
								golput = parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p)-parseFloat(result[i][''].tdk_sah);
							} 
							
							baruHtml += '<td>'+(parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2)+'% - '+result[i][''].suara+'</td>';
						
							id_kecamatan = result[i]['Kecamatan'].id;
						}
						if(result[i][''].sah==null || result[i][''].sah==0){
							baruHtml += '<td colspan="3"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td></tr>';
						} else {
							baruHtml += '<td>'+result[i][''].sah+'</td><td>'+(golput-parseFloat(result[i][''].sah))+'</td></tr>';
						}
						$('#hitung-kecamatan-table tbody').html(baruHtml);
						$('#hitung-kecamatan-table').table( 'rebuild' );	
					}
				}
			});              
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: hitung.server+'c=kecamatan_input&id_kabupaten='+hitung.kabupatenS.id,
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
				url: hitung.server+'c=calon_read',
				success: function (result) {
					if(result.status!=false) {
						//alert(JSON.stringify(result)); 
						var thead = '<tr class="ui-bar-d"><th>No</th><th data-priority="7">Kabupaten</th><th data-priority="2">Pemilih Tetap</th><th data-priority="5">Suara Tdk Sah</th>';
						var formhitung = '';
						for(var i in result){
							for(var j in result[i]){
								thead += '<th>('+result[i][j].no_urut+') '+result[i][j].alias+'</th>';
								formhitung += '<label class="ui-accessible">Suara ('+result[i][j].no_urut+') '+result[i][j].alias+' :</label>'+
									'<input type="number" name="no-urut-'+result[i][j].id+'" required />';
							}
						}
						thead += '<th data-priority="4">Suara Sah</th><th data-priority="3">Golput</th></tr>';
						$('#hitung-kabupaten-table thead').html(thead);
						$('#hitung-kabupaten-form-input-hitung').html(formhitung);
					}
				},
			});
			$('#hitung-tab-control').trigger('create');
		},
		
		_select : function(selected) {
			ajaxP.Request({
				url: hitung.server+'c=kabupaten_select',
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
			ajaxP.Request({
				url: hitung.server+'c=kabupaten_read',
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
										baruHtml += '<td colspan="3"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td></tr>';
									} else {
										baruHtml += '<td>'+result[i-1][''].sah+'</td><td>'+(golput-parseFloat(result[i-1][''].sah))+'</td></tr>';
									}
									no++;
								}
								baruHtml += '<tr>'+
								'<th>'+no+'</th>'+
								'<th><a href="#" onclick="hitung.tab._kecamatan(\''+result[i]['Kabupaten'].id+'\')">'+result[i]['Kabupaten'].nama+'</a></th>'+
								'<td>'+(parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p))+'</td>'+
								'<td>'+result[i][''].tdk_sah+'</td>';
								golput = parseFloat(result[i][''].pt_l)+parseFloat(result[i][''].pt_p)-parseFloat(result[i][''].tdk_sah);
							} 
							
							baruHtml += '<td>'+(parseFloat(result[i][''].suara)/parseFloat(result[i][''].sah)*100).toFixed(2)+'% - '+result[i][''].suara+'</td>';
						
							id_kabupaten = result[i]['Kabupaten'].id;
						}
						if(result[i][''].sah==null || result[i][''].sah==0){
							baruHtml += '<td colspan="3"><strong>Ket : data hitungan suara yang masuk masih NULL.</strong></td></tr>';
						} else {
							baruHtml += '<td>'+result[i][''].sah+'</td><td>'+(golput-parseFloat(result[i][''].sah))+'</td></tr>';
						}
						$('#hitung-kabupaten-table tbody').html(baruHtml);
						$('#hitung-kabupaten-table').table( 'rebuild' );
					}
					
				}
			});
			
		},
		
		_input : function(){
			
			ajaxP.Request({
				url: hitung.server+'c=kabupaten_input',
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

var calon 	= {
	server : host+'_control/admin/calon.php?ajax&',
	//server : 'http://localhost/myjob/ipna/_control/admin/calon.php?ajax&',
	
	calon : {
		
		_uploadFoto : function(el,id) {
			var filedata = $('#'+el)[0].files[0];
			var formdata = new FormData();
			
			//alert(filedata.name+' | '+filedata.size+' | '+filedata.type);
			formdata.append('file1', filedata);
			
			$.ajax({
                url: calon.server+'c=calon_uploadFoto&id='+id, 
                cache: false, contentType: false, processData: false,
                data: formdata, type: 'post', dataType: 'json',
                beforeSend: function() { $.mobile.loading('show'); },
				complete  : function() { $.mobile.loading('hide'); },
				error     : function(request,error) { alert('Upload abortes, Network error has occurred please try again!'); },
				success   : function(result){
                    if(result.status) {
						$('#calon-upload-slider-label').html('Status : complete');
						alert(result.message); 
						$('#calon-foto-popup-input').popup( 'close' );
						$('#calon-form-upload')[0].reset();
						calon.calon._read();
					} else {
						alert('gagal '+result.message); 
					}
                },
				xhr       : function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener('progress', function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							$('#calon-upload-slider-label').html('Status : loading... '+evt.loaded+' byte from '+evt.total+' byte');
							$('#calon-upload-slider').val(percentComplete).slider('refresh');
							
						}
					}, false);	return xhr;
				}
			});	
		},
		
		_delete : function(id) {
			$.ajax({
				url: calon.server+'c=calon_delete&id='+id,
				type: 'post',                   
				async: 'true',
				dataType: 'json',
				beforeSend: function() { $.mobile.loading('show'); },
				complete: function()   { $.mobile.loading('hide'); },
				success: function (result) {
					if(result.status) {
						alert(result.message);
						calon.calon._read();
					} else {
						alert('gagal '+result.message); 
					}
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});
		}, 
		
		_update : function() {
    
			$.ajax({
				url: calon.server+'c=calon_update',
				data: $('#calon-form-input').serialize(),
				type: 'post',                   
				async: 'true',
				dataType: 'json',
				beforeSend: function() { $.mobile.loading('show'); },
				complete: function()   { $.mobile.loading('hide'); },
				success: function (result) {
					if(result.status) {
						alert(result.message);
						$('#calon-popup-input').popup( 'close' );
						$('#calon-form-input')[0].reset();
						calon.calon._read();
					} else {
						alert(result.message); 
					}
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
			
        },
		
		_readUpdate : function(id) {
			$.ajax({
				url: calon.server+'c=calon_readUpdate&id='+id,
				type: 'post',                   
				async: 'true',
				dataType: 'json',
				beforeSend: function() { $.mobile.loading('show'); },
				complete: function()   { $.mobile.loading('hide'); },
				success: function (result) {
					if(!result){
						alert('Warning! Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = '';
						for (var i in result){
							for (var j in result[i]){
								calon.calon._pFU();
								$('#calon-form-input input[name="id"]').val(result[i][j].id);
								$('#calon-form-input input[name="no_urut"]').val(result[i][j].no_urut);
								$('#calon-form-input input[name="nama"]').val(result[i][j].nama);
								$('#calon-form-input input[name="alias"]').val(result[i][j].alias);
								$('#calon-form-input textarea[name="ket"]').html(result[i][j].ket);
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
					}
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
			
		},
		
		_read : function() {
			ajaxP.Request({
				url: calon.server+'c=calon_read',
				success: function (result) {
					if(!result){
						alert('Warning! Gagal mengambil database');
					} else {
						//alert(JSON.stringify(result)); 
						var baruHtml = ''; var no=1;
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<li data-filtertext="NASDAQ:">'+
										'<table style="clear:both;"><tr>'+
										'<td><img src="'+host+'_view/file/images/calon/'+result[i][j].gambar+'" style="width:12vw; max-width:100px; border-radius:7px;">'+
										'<br/><a href="#calon-foto-popup-input" onclick="calon.calon._pFUpload(\''+result[i][j].id+'\');" data-transition="pop" data-position-to="window" data-rel="popup" data-iconpos="notext" class="ui-btn ui-icon-camera ui-btn-icon-notext ui-shadow ui-corner-all" data-role="button"></a></td>'+
										'<td style="width:100%; padding-left:1%;"><span><strong>'+result[i][j].no_urut+'. '+result[i][j].alias+'</strong><br/>'+result[i][j].nama+'</span><br/><p style="font-size: .75em; font-weight: 400;">'+result[i][j].ket+'</p></td>'+
										'<td><a onclick="calon.calon._readUpdate(\''+result[i][j].id+'\');" href="#calon-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a></td>'+
										'<td><a onclick="calon.calon._pBD(\''+result[i][j].id+'\');" href="#calon-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+
									'</tr></table>'+
								'</li>';no++;
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('#readcalon').html('');
						$('#readcalon').html(baruHtml);
						$('#readcalon').listview('refresh');
					}
				},
			});                   
			
		},
		
		_input : function() {
    
			$.ajax({
				url: calon.server+'c=calon_input',
				data: $('#calon-form-input').serialize(),
				type: 'post',                   
				async: 'true',
				dataType: 'json',
				beforeSend: function() { $.mobile.loading('show'); },
				complete: function()   { $.mobile.loading('hide'); },
				success: function (result) {
					if(result.status) {
						alert(result.message);
						$('#calon-popup-input').popup( 'close' );
						$('#calon-form-input')[0].reset();
						calon.calon._read();
					} else {
						alert(result.message); 
					}
				},
				error: function (request,error) {alert('Network error has occurred please try again!');
				}
			});                   
			
        },
		
		_pFI : function(){
			$('#calon-popup-input h3').html('Input Data calon');
			$('#calon-form-input')[0].reset();
			$('#calon-form-input textarea').html('');
			$('#calon-form-input').attr('onsubmit','calon.calon._input(); return false;');
		}, 
		
		_pFU : function(id){
			$('#calon-popup-input h3').html('Edit Data calon');
			$('#calon-form-input').attr('onsubmit','calon.calon._update(); return false;');
		},

		_pFUpload : function(id){
			$('#calon-form-upload input[name="submit"]').attr('onclick','calon.calon._uploadFoto(\'calon-foto\','+id+'); return false;');
		},
		
		_pBD : function(id){
			$('#calon-popup-delete').html('<a onclick="calon.calon._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		}
			
	},
		
	tab : {
			
		_calon : function(){
			$('#calon-tab').css('background','#FFD500');
			$('#calon').show();
			calon.calon._read();
		},
		
	},
};	

var admin 	= {
	//server : 'http://localhost/myjob/ipna/_control/admin/admin.php?ajax&',
	server : host+'_control/admin/admin.php?ajax&',
	level : {},
	kabupatenS : {},
	
	kecamatan : {
		
		_delete : function(id) {
			ajaxP.Request({
				url: admin.server+'c=kecamatan_delete&id='+id,
				success: function (result) {
					if(result.status) {
						admin.kecamatan._read(admin.kabupatenS.id);
					}
				},
			});
		}, 
		
		_update : function() {
			if(validatePassword(3)){
				ajaxP.Request({
					url: admin.server+'c=kecamatan_update',
					data: $('#admin-kecamatan-form-input').serialize(),
					success: function (result) {
						if(result.status) {
							$('#admin-kecamatan-popup-input').popup( 'close' );
							$('#admin-kecamatan-form-input')[0].reset();
							admin.kecamatan._read(admin.kabupatenS.id);
						}
					},
				});
			}
        },
		
		
		_kabupaten_select : function() {
			ajaxP.Request({
				url: admin.server+'c=kabupaten_select',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih Kabupaten -</option>'; 
					for (var i in result){
						for (var j in result[i]){
							baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
					$('#admin-kabupaten-select-1').html(baruHtml);
				}
			});      
		},
		
		_select : function(id_kabupaten) {
			ajaxP.Request({
				url: admin.server+'c=kecamatan_select&id_kabupaten='+id_kabupaten,
				success: function (result) {
					if(result.status!=false){
						var baruHtml = '<option value="">- Pilih Kecamatan -</option>'; 
						for (var i in result){
							for (var j in result[i]){
								baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
								//alert(JSON.stringify(result[i][j].nama));
							}
						}
						$('#admin-kecamatan-select').html(baruHtml);
						$('#admin-kecamatan-select').selectmenu('enable');
					}else{
						$('#admin-kecamatan-table-div').hide();
					}
					$('#admin-kecamatan-select').selectmenu('refresh');
					
				}
			});      
		},
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: admin.server+'c=kecamatan_readUpdate&id='+id,
				success: function (result) {
					var baruHtml = '';
					for (var i in result){
						for (var j in result[i]){
							admin.kecamatan._pFU();
							$('#admin-kecamatan-form-input input[name="id"]').val(result[i][j].id);
							$('#admin-kecamatan-form-input input[name="nama"]').val(result[i][j].nama);
							$('#admin-kecamatan-form-input input[name="phone"]').val(result[i][j].phone);
							$('#admin-kecamatan-form-input input[name="password"]').val(result[i][j].password);
							$('#admin-kecamatan-form-input input[name="confirm_password"]').val(result[i][j].password);
							$('#admin-kecamatan-select').val(result[i][j].id_wilayah).selectmenu('refresh');
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
				},
			});                   
			
		},
		
		_read : function(id_kabupaten) {
			var baruHtml = ''; 
			ajaxP.Request({
				url: admin.server+'c=kecamatan_read&id_kabupaten='+id_kabupaten,
				success: function (result) {
					//alert(JSON.stringify(result));
					if(result.status==false){
						$('#admin-kecamatan-table tbody').html(baruHtml);
						$('#admin-kecamatan-table').table( 'rebuild' );
						
					} else {		
						var no=1;
						for (var i in result){
							baruHtml += '<tr><th>'+no+'</th>'+
								'<td>'+result[i]['Kecamatan'].nama+'</td>'+
								'<td>'+result[i]['Admin'].nama+'</td>'+
								'<td>'+result[i]['Admin'].phone+'</td>'+
								'<td><a onclick="admin.kecamatan._readUpdate(\''+result[i]['Admin'].id+'\');" href="#admin-kecamatan-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a>'+
								'<a onclick="admin.kecamatan._pBD(\''+result[i]['Admin'].id+'\');" href="#admin-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+

							'</tr>'; no++;
							//alert(JSON.stringify(result[i]['Admin'].nama));
						}
						$('#admin-kecamatan-table tbody').html(baruHtml);
						$('#admin-kecamatan-table').table( 'rebuild' );
					}
					$('#admin-kecamatan-table-div').show();
					admin.kabupatenS = {'id':id_kabupaten};
					admin.kecamatan._select(id_kabupaten);
				}
			});
			
		},
		
		_input : function(){
			if(validatePassword(3)){
				ajaxP.Request({
					url: admin.server+'c=kecamatan_input&level='+admin.level.level,
					data: $('#admin-kecamatan-form-input').serialize(),
					success: function (result) {
						if(result.status) {	
							$('#admin-kecamatan-popup-input').popup( 'close' );
							$('#admin-kecamatan-form-input')[0].reset();
							admin.kecamatan._read(admin.kabupatenS.id);
						} 
					}
				});
			}
		},
		
		_pFU : function(id){
			$('#admin-kecamatan-popup-input h3').html('Edit Data Admin kecamatan');
			$('#admin-kecamatan-form-input').attr('onsubmit','admin.kecamatan._update(); return false;');
		},
	
		_pBD : function(id){
			$('#admin-popup-delete').html('<a onclick="admin.kecamatan._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		},
		
		_pFI : function(){
			$('#admin-kecamatan-popup-input h3').html('Input Data Admin kecamatan');
			$('#admin-kecamatan-form-input')[0].reset();
			$('#admin-kecamatan-form-input').attr('onsubmit','admin.kecamatan._input(); return false;');
		}, 
	},
	
	kabupaten : {
		
		_delete : function(id) {
			ajaxP.Request({
				url: admin.server+'c=kabupaten_delete&id='+id,
				success: function (result) {
					if(result.status) {
						admin.kabupaten._read();
					}
				},
			});
		}, 
		
		
		_update : function() {
			if(validatePassword(2)){
				ajaxP.Request({
					url: admin.server+'c=kabupaten_update',
					data: $('#admin-kabupaten-form-input').serialize(),
					success: function (result) {
						if(result.status) {
							$('#admin-kabupaten-popup-input').popup( 'close' );
							$('#admin-kabupaten-form-input')[0].reset();
							admin.kabupaten._read();
						}
					},
				});    
			}
        },
		
		_select : function() {
			ajaxP.Request({
				url: admin.server+'c=kabupaten_select',
				success: function (result) {
					var baruHtml = '<option value="">- Pilih kabupaten -</option>'; 
					for (var i in result){
						for (var j in result[i]){
							baruHtml += '<option value="'+result[i][j].id+'">'+result[i][j].nama+'</option>';
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
					$('#admin-kabupaten-select').html(baruHtml);
				}
			});      
		},
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: admin.server+'c=kabupaten_readUpdate&id='+id,
				success: function (result) {
					var baruHtml = '';
					for (var i in result){
						for (var j in result[i]){
							admin.kabupaten._pFU();
							$('#admin-kabupaten-form-input input[name="id"]').val(result[i][j].id);
							$('#admin-kabupaten-form-input input[name="nama"]').val(result[i][j].nama);
							$('#admin-kabupaten-form-input input[name="phone"]').val(result[i][j].phone);
							$('#admin-kabupaten-form-input input[name="password"]').val(result[i][j].password);
							$('#admin-kabupaten-form-input input[name="confirm_password"]').val(result[i][j].password);
							$('#admin-kabupaten-select').val(result[i][j].id_wilayah).selectmenu('refresh');
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
				},
			});                   
			
		},
		
		_read : function() {
			ajaxP.Request({
				url: admin.server+'c=kabupaten_read',
				success: function (result) {
					//alert(JSON.stringify(result));
					var baruHtml = ''; 
					var no=1;
					for (var i in result){
						baruHtml += '<tr><th>'+no+'</th>'+
							'<td>'+result[i]['Kabupaten'].nama+'</td>'+
							'<td>'+result[i]['Admin'].nama+'</td>'+
							'<td>'+result[i]['Admin'].phone+'</td>'+
							'<td><a onclick="admin.kabupaten._readUpdate(\''+result[i]['Admin'].id+'\');" href="#admin-kabupaten-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a>'+
							'<a onclick="admin.kabupaten._pBD(\''+result[i]['Admin'].id+'\');" href="#admin-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+

						'</tr>'; no++;
						//alert(JSON.stringify(result[i]['Admin'].nama));
					}
					$('#admin-kabupaten-table tbody').html(baruHtml);
					$('#admin-kabupaten-table').table( 'rebuild' );
				}
			});
			
		},
		
		_input : function(){
			if(validatePassword(2)){
				ajaxP.Request({
					url: admin.server+'c=kabupaten_input&level='+admin.level.level,
					data: $('#admin-kabupaten-form-input').serialize(),
					success: function (result) {
						if(result.status) {	
							$('#admin-kabupaten-popup-input').popup( 'close' );
							$('#admin-kabupaten-form-input')[0].reset();
							admin.kabupaten._read();
						} 
					}
				});
			}
		},
		
		_pFU : function(id){
			$('#admin-kabupaten-popup-input h3').html('Edit Data Admin Kabupaten');
			$('#admin-kabupaten-form-input').attr('onsubmit','admin.kabupaten._update(); return false;');
		},
	
		_pBD : function(id){
			$('#admin-popup-delete').html('<a onclick="admin.kabupaten._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		},
		
		_pFI : function(){
			$('#admin-kabupaten-popup-input h3').html('Input Data Admin kabupaten');
			$('#admin-kabupaten-form-input')[0].reset();
			$('#admin-kabupaten-form-input').attr('onsubmit','admin.kabupaten._input(); return false;');
		}, 
	},
	
	
	pusat : {
		
		_delete : function(id) {
			ajaxP.Request({
				url: admin.server+'c=pusat_delete&id='+id,
				success: function (result) {
					if(result.status) {
						admin.pusat._read();
					}
				},
			});
		}, 
		
		
		_update : function() {
			if(validatePassword(1)){
				ajaxP.Request({
					url: admin.server+'c=pusat_update',
					data: $('#admin-pusat-form-input').serialize(),
					success: function (result) {
						if(result.status) {
							$('#admin-pusat-popup-input').popup( 'close' );
							$('#admin-pusat-form-input')[0].reset();
							admin.pusat._read();
						}
					},
				});   
			}
        },
		
		_readUpdate : function(id) {
			ajaxP.Request({
				url: admin.server+'c=pusat_readUpdate&id='+id,
				success: function (result) {
					var baruHtml = '';
					for (var i in result){
						for (var j in result[i]){
							admin.pusat._pFU();
							$('#admin-pusat-form-input input[name="id"]').val(result[i][j].id);
							$('#admin-pusat-form-input input[name="nama"]').val(result[i][j].nama);
							$('#admin-pusat-form-input input[name="phone"]').val(result[i][j].phone);
							$('#admin-pusat-form-input input[name="password"]').val(result[i][j].password);
							$('#admin-pusat-form-input input[name="confirm_password"]').val(result[i][j].password);
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
				},
			});                   
			
		},
		
		_read : function() {
			ajaxP.Request({
				url: admin.server+'c=pusat_read',
				success: function (result) {
					var baruHtml = ''; 
					var no=1;
					for (var i in result){
						for(var j in result[i]){
							baruHtml += '<tr><th>'+no+'</th>'+
								'<td>'+result[i][j].nama+'</td>'+
								'<td>'+result[i][j].phone+'</td>'+
								'<td><a onclick="admin.pusat._readUpdate(\''+result[i][j].id+'\');" href="#admin-pusat-popup-input" data-transition="pop" data-position-to="window" data-rel="popup" class="ui-btn ui-icon-edit ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">edit</a>'+
								'<a onclick="admin.pusat._pBD(\''+result[i][j].id+'\');" href="#admin-delete-data" data-transition="pop" data-position-to="window" data-rel="popup"class="ui-btn ui-icon-delete ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext" style="margin:0;">delete</a></td>'+

							'</tr>'; no++;
							//alert(JSON.stringify(result[i][j].nama));
						}
					}
					$('#admin-pusat-table tbody').html(baruHtml);
					$('#admin-pusat-table').table( 'rebuild' );
				}
			});
			
		},
		
		_input : function(){
			if(validatePassword(1)){
				ajaxP.Request({
					url: admin.server+'c=pusat_input&level='+admin.level.level,
					data: $('#admin-pusat-form-input').serialize(),
					success: function (result) {
						if(result.status) {	
							$('#admin-pusat-popup-input').popup( 'close' );
							$('#admin-pusat-form-input')[0].reset();
							admin.pusat._read();
						} 
					}
				});
			}
		},
		
		
		_pFU : function(id){
			$('#admin-pusat-popup-input h3').html('Edit Data Admin Pusat');
			$('#admin-pusat-form-input').attr('onsubmit','admin.pusat._update(); return false;');
		},
	
		_pBD : function(id){
			$('#admin-popup-delete').html('<a onclick="admin.pusat._delete(\''+id+'\')" class="ui-shadow ui-btn ui-corner-all ui-btn-b ui-icon-check ui-btn-icon-left ui-btn-inline ui-mini" data-rel="back">Oke</a>'+
				'<a class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-mini" data-rel="back">Cancel</a>');
		},
		
		_pFI : function(){
			$('#admin-pusat-popup-input h3').html('Input Data Admin Pusat');
			$('#admin-pusat-form-input')[0].reset();
			$('#admin-pusat-form-input').attr('onsubmit','admin.pusat._input(); return false;');
		}, 
	},
		
	tab : {
			
		_pusat : function(){
			admin.tab._closeall();
			$('#admin-pusat-tab').css('background','#FFD500');
			$('#admin-pusat').show();
			admin.level = {'level':'pusat','id_wilayah':''};
			admin.pusat._read();
		},
		
		_kabupaten : function(){
			admin.tab._closeall();
			$('#admin-kabupaten-tab').css('background','#FFD500');
			$('#admin-kabupaten').show();
			admin.kabupaten._read();
			admin.level = {'level':'kabupaten','id_wilayah':''};
			admin.kabupaten._select();
		},
		
		_kecamatan : function(){
			admin.tab._closeall();
			$('#admin-kecamatan-tab').css('background','#FFD500');
			$('#admin-kecamatan').show();
			admin.level = {'level':'kecamatan','id_wilayah':''};
			admin.kecamatan._kabupaten_select();
			//admin.kecamatan._read();
		},
		
		_closeall : function(){
			$('#admin-pusat').hide();
			$('#admin-kabupaten').hide();
			$('#admin-kecamatan').hide();
			$('#admin-kecamatan-table-div').hide();
			$('#admin-kabupaten-tab').css('background','#f9f9f9');
			$('#admin-pusat-tab').css('background','#f9f9f9');
			$('#admin-kecamatan-tab').css('background','#f9f9f9');
			
		},
		
	},
};

var login 	= {
	//server : 'http://localhost/myjob/ipna/_control/admin/admin.php?ajax&',
	server : host+'_control/login.php?ajax&',
	
	form : {
		_input : function(){
			$('#login-form-error').html('');
			ajaxP.Request({
				url: login.server+'c=login_input',
				data: $('#login-form-input').serialize(),
				success: function (result) {
					if(result.status==false){
						//$('#login-form-input')[0].reset();
						$('#login-form-error').html('<div class="ui-body ui-icon-minus ui-body-a ui-corner-all" style="background:#FB7C51; color:#fff;">'+
							'<button class="ui-btn ui-icon-minus ui-shadow ui-corner-all ui-btn-inline ui-btn-icon-notext">minus</button>Username atau Password anda salah.'+
							'</div>');
					} else {	
						alert(JSON.stringify(result));
						alert(result['0']['Admin'].nama);
						
						window.localStorage.setItem("rcuserID", result['0']['Admin'].id);
						window.localStorage.setItem("rcuserNAME", result['0']['Admin'].nama);
						window.localStorage.setItem("rcuserLEVEL", result['0']['Admin'].level);
						window.localStorage.setItem("rcuserID_WIL", result['0']['Admin'].id_wilayah);
						window.localStorage.setItem("rclogIN", 1);
						if(window.localStorage.getItem("rclogIN") == 1) {
							window.location = "index.html";
							//$.mobile.changePage("index.html", {transition: "slide"}, true, true);
							/*$("#index").on("pageshow", function(){
								alert("hello");
							});*/
						}
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
				alert(JSON.stringify(request)); 
				//alert('Network error has occurred please try again!'); 
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
		