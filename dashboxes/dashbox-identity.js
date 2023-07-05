/**
 * Script functions file
 */
( function( $ ) {
    // Document Ready
    $(document).ready(function(){
        //alert('DOM Ready');

        $("#mkwvs_dashbox_identity_save").click( function(e) {
            e.preventDefault();

            var nonce = $('#mkwvs_dashbox_social_nonce').val();

            // Get Coordonnees Infos

            var email   = $('#mkwvs_dashbox_coordonnees_email').val();
            var phone   = $('#mkwvs_dashbox_coordonnees_phone').val();
            var address = $('#mkwvs_dashbox_coordonnees_address').val();
            var zipcode = $('#mkwvs_dashbox_coordonnees_zip_code').val();
            var city    = $('#mkwvs_dashbox_coordonnees_city').val();
            var coordonnees = new Array(email, phone, address, zipcode, city);


            // Get Social Infos

            var actifs = new Array();
            $('#mkwvs_dashbox_identity .actif').each(function(){ if ($(this).prop('checked') === true) actifs.push('1'); else actifs.push('0');});
            var labels = new Array();
            $('#mkwvs_dashbox_identity .libelle').each(function(){labels.push($(this).text());});
            var links = new Array();
            $('#mkwvs_dashbox_identity .link').each(function(){links.push($(this).val());});

            // Concatenation
            var socials = new Array();
            for (var i = 0 ; i < actifs.length ; i++){
                var social_item = new Array(actifs[i], labels[i], links[i]);
                socials.push(social_item);
            }

            // Flush Existing Message
            $('#mkwvs_dashbox_identity div.updated').each(function(){$(this).remove();});
            $('#mkwvs_dashbox_identity div.error').each(function(){$(this).remove();});

            // Launch Ajax Request & Manage Response
            $.ajax({
                type : "post", url : ajaxurl,
                data : { action: "mkwvs_dashbox_identity_save", nonce: nonce, socials:socials, coordonnees:coordonnees },
                beforeSend: function() {  $('#mkwvs_dashbox_identity span.spinner').css('visibility','visible'); },
                success: function(response) {
                    if (response.success === true){
                        $('#mkwvs_dashbox_identity .content').before('<div class="updated">Informations mises à jours !</div>');
                    } else if(response.success === false){
                        $('#mkwvs_dashbox_identity .content').before('<div class="error">Une erreur est survenue</div>');
                    }
                },
                error: function(){
                    $('#mkwvs_dashbox_identity .content').before('<div class="error">Une erreur est survenue</div>');
                    $('#mkwvs_dashbox_identity span.spinner').css('visibility','hidden');
                },
                complete: function(){ $('#mkwvs_dashbox_identity span.spinner').css('visibility','hidden'); }
            });

        });

    });

    $(window).load(function(){
        //alert('Window Loaded');
    });

} )( jQuery );


