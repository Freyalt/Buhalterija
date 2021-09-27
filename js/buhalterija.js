jQuery( function() {
    jQuery( "#dataNuo" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );

jQuery( function() {
    jQuery( "#dataIki" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
jQuery( function() {
    jQuery( "#israsymo_data" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );


jQuery( '.pirkejoDuomenys' ).click(function() {
	jQuery(this).next().slideToggle( "slow", function() {
  });
});

jQuery( ".open-forma" ).click(function() {
  jQuery( ".nauja-imone-forma" ).slideToggle( "slow", function() {
  });
});

jQuery( '.clickTarget' ).click(function(e) {
	if(jQuery(e.target).is('.input')) {
        return;
    } else {
    	jQuery(this).closest('tr').next('tr').slideToggle( "fast", function() { });
    }
});
jQuery(document).ready(function(){
	jQuery('.redaguoti').click(function(e){
		var id = jQuery(this).attr("name");
		var imones_pavadinimas = jQuery('#imones_pavadinimas-'+id).attr('value');
		var salis = jQuery('#salis-'+id).attr('value');
		var miestas = jQuery('#miestas-'+id).attr('value');
		var gatve = jQuery('#gatve-'+id).attr('value');
		var namo_nr = jQuery('#namo_nr-'+id).attr('value');
		var pasto_kodas = jQuery('#pasto_kodas-'+id).attr('value');
		var telefonas = jQuery('#telefonas-'+id).attr('value');
		var elpastas = jQuery('#elpastas-'+id).attr('value');
		var imones_kodas = jQuery('#imones_kodas-'+id).attr('value');
		var pvm_kodas = jQuery('#pvm_kodas-'+id).attr('value');
		var banko_kodas = jQuery('#banko_kodas-'+id).attr('value');
		var banko_saskaita = jQuery('#banko_saskaita-'+id).attr('value');

		if(jQuery('.nauja-imone-forma').is(':visible')) { 
			jQuery( ".nauja-imone-forma" ).slideUp( "slow", function() {
				jQuery("#id").remove();
				jQuery("#spamID").remove();
				jQuery( ".nauja-imone-forma" ).trigger("reset"); 
				jQuery("#imones_pavadinimas").val(imones_pavadinimas);
				jQuery("#salis").val(salis);
				jQuery("#miestas").val(miestas);
				jQuery("#gatve").val(gatve);
				jQuery("#namo_nr").val(namo_nr);
				jQuery("#pasto_kodas").val(pasto_kodas);
				jQuery("#telefonas").val(telefonas);
				jQuery("#elpastas").val(elpastas);
				jQuery("#imones_kodas").val(imones_kodas);
				jQuery("#pvm_kodas").val(pvm_kodas);
				jQuery("#banko_kodas").val(banko_kodas);
				jQuery("#banko_saskaita").val(banko_saskaita);
				jQuery("#saugotiTiekeja").val('Išsaugot pakeistus įmonės '+imones_pavadinimas+' duomenis');
				jQuery('<spam id="spamID"> Įmonės ID: '+id+' </spam>').insertAfter("#valytiForma");
				jQuery('<input type="text" id="id" class="hiddePirkejas" name="id" value="'+id+'">').insertAfter("#spamID");
			}).slideDown( "slow", function() {});
		} else {
			jQuery("#id").remove();
			jQuery("#spamID").remove();
			jQuery( ".nauja-imone-forma" ).trigger("reset"); 
			jQuery("#imones_pavadinimas").val(imones_pavadinimas);
			jQuery("#salis").val(salis);
			jQuery("#miestas").val(miestas);
			jQuery("#gatve").val(gatve);
			jQuery("#namo_nr").val(namo_nr);
			jQuery("#pasto_kodas").val(pasto_kodas);
			jQuery("#telefonas").val(telefonas);
			jQuery("#elpastas").val(elpastas);
			jQuery("#imones_kodas").val(imones_kodas);
			jQuery("#pvm_kodas").val(pvm_kodas);
			jQuery("#banko_kodas").val(banko_kodas);
			jQuery("#banko_saskaita").val(banko_saskaita);
			jQuery("#saugotiTiekeja").val('Išsaugot pakeistus įmonės '+imones_pavadinimas+' duomenis');
			jQuery('<spam id="spamID"> Įmonės ID: '+id+' </spam>').insertAfter("#valytiForma");
			jQuery('<input type="text" id="id" class="hiddePirkejas" name="id" value="'+id+'">').insertAfter("#spamID");
			jQuery( ".nauja-imone-forma" ).slideDown( "slow", function() {});
		}
	});
});
jQuery('#valytiForma').click(function(){ 
	jQuery( ".nauja-imone-forma" ).slideUp( "slow", function() {
		jQuery( ".nauja-imone-forma" ).trigger("reset"); 
		jQuery("#id").remove();
		jQuery("#spamID").remove();
		jQuery("#saugotiTiekeja").val('Išsaugoti');
	}).slideDown( "slow", function() {});
});

function toTop(){
	jQuery("html,body").animate({scrollTop: jQuery("#open-forma").offset().top - 40}, 600);
}



jQuery(function() {
       jQuery('.trinti').click(function(e) {
       	var id = jQuery(this).attr('name');
       	var imones_pavadinimas = jQuery('#imones_pavadinimas-'+id).attr('value');
        e.preventDefault();
        var c = confirm('Ar tikrai norite ištrinti įmonės "'+imones_pavadinimas+'" duomenis?');
        if(c){
            jQuery('#delete-'+id).submit();
          }
    });
});

jQuery(function() {
	jQuery('#pazymeti_visus_tiekejus').click(function() {
		jQuery('.cb').prop('checked', true);
	});
});

jQuery(function() {
    jQuery('#trr').click(function(e) {
       	var selected = [];
		jQuery('#tiekejai_td input:checked').each(function() {
		    selected.push(jQuery(this).attr('name'));
		});
		var x = selected.toString();
		jQuery("#tzid").val(x);
        e.preventDefault();
        if(confirm('Ar tikrai norite ištrinti pažymėtų įmonių duomenis?')){
            jQuery('#trrr').submit();
        }
    });
});
jQuery(function() { 
	jQuery('#tdivform').click(function(){
		jQuery('#nauja-saskaita').slideToggle( "slow", function() {});
	});
});
jQuery(function() {
	jQuery('#valytiSaskaitat').click(function() {
		jQuery('#nauja-saskaita').slideToggle( "slow", function() {
			jQuery('#nauja-saskaita')[0].reset();
			jQuery('#apmokejimas').find('option:selected').removeAttr("selected");
			jQuery("#tiekejas").val('tt');
			jQuery("#tiekejas").selectpicker('refresh');
			jQuery('#styleID').html('').hide();
			jQuery('#rsaskaitos_id').val('');
		});
		jQuery('#nauja-saskaita').slideToggle( "slow", function() {});
	});
});
jQuery(function() {
	jQuery('.saskaitosSp').click(function(){
		var id = jQuery(this).attr("name");
		jQuery("#saskaitosRd-"+id).slideToggle('fast', function() {

		});
	});
});
jQuery(function() {
       jQuery('.trintiSaskaita').click(function(e) {
       	var id = jQuery(this).attr('name');
       	var numeris = jQuery(this).attr('numeris');

        e.preventDefault();
        var c = confirm('Ar tikrai norite ištrinti sąskaitos "'+numeris+'" duomenis?');
        if(c){
            jQuery('#trintiSaskaita-'+id).submit();
          }
    });
});
jQuery(document).ready(function(){
	jQuery('.redaguotiSaskaita').click(function(e){ 
		var id = jQuery(this).attr("name");
		var data = jQuery('#israsymo_data-'+id).attr("value");
		var saskaitos_nr = jQuery('#saskaitos_nr-'+id).attr("value");
		var suma_be_pvm = jQuery('#suma_be_pvm-'+id).attr("value");
		var pvm = jQuery('#pvm-'+id).attr("value");
		var suma_su_pvm = Number(suma_be_pvm) + Number(pvm);
		suma_su_pvm = suma_su_pvm.toFixed(2);
		var apmokejimas = jQuery('#apmokejimas-'+id).attr("value");
		var imones_pavadinimas = jQuery('#imones_pavadinimas-'+id).attr("value");
		jQuery('html, body').animate({scrollTop: jQuery("#tdivform").offset().top - 40}, 600);


		if(jQuery('#nauja-saskaita').is(':visible')) { 
			jQuery('#nauja-saskaita').slideToggle( "slow", function() {
				jQuery('#styleID').show().html('Redaguojamos sąskaitos ID '+id);
				jQuery('#rsaskaitos_id').val(id);
				jQuery('#israsymo_data').val(data);
				jQuery('#israsymo_suma_be_pvm').val(suma_be_pvm);
				jQuery('#israsymo_suma_su_PVM').val(suma_su_pvm);
				jQuery('#israsymo_suma_be_pvm').val(suma_be_pvm);
				jQuery('#saskaitos_nr').val(saskaitos_nr);
				jQuery('#apmokejimas option[value="'+apmokejimas+'"]').attr('selected', true);
				jQuery("#tiekejas").val(imones_pavadinimas);
				jQuery("#tiekejas").selectpicker('refresh');
			});
			jQuery('#nauja-saskaita').slideToggle( "slow", function() {});
		} else {
			jQuery('#styleID').show().html('Redaguojamos sąskaitos ID '+id);
			jQuery('#rsaskaitos_id').val(id);
			jQuery('#israsymo_data').val(data);
			jQuery('#israsymo_suma_be_pvm').val(suma_be_pvm);
			jQuery('#israsymo_suma_su_PVM').val(suma_su_pvm);
			jQuery('#israsymo_suma_be_pvm').val(suma_be_pvm);
			jQuery('#saskaitos_nr').val(saskaitos_nr);
			jQuery('#apmokejimas option[value="'+apmokejimas+'"]').attr('selected', true);
			jQuery("#tiekejas").val(imones_pavadinimas);
			jQuery("#tiekejas").selectpicker('refresh');
			jQuery('#nauja-saskaita').slideToggle( "slow", function() {});
		}
		
	});
});