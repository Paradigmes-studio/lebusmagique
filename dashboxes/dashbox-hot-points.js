/**
 * Script functions file
 */
( function( $ ) {
        // Document Ready
	$(document).ready(function(){
            
            $("#hot_points_save").click( function(e) {
                e.preventDefault();
                var $this = $(this);
                var nonce = $('#foool_dashbox_hot_points_nonce').val();
                
                var hot_point_libelle_1 = $('#libelle_hot_point_1').val();                
                var hot_point_value_1 = $('#value_hot_point_1').val();
                var hot_point_unite_1 = $('#unite_hot_point_1').val();
                var hot_points_1 = [hot_point_value_1,hot_point_libelle_1, hot_point_unite_1];
                
                var hot_point_libelle_2 = $('#libelle_hot_point_2').val();                
                var hot_point_value_2 = $('#value_hot_point_2').val();
                var hot_point_unite_2 = $('#unite_hot_point_2').val();
                var hot_points_2 = [hot_point_value_2,hot_point_libelle_2, hot_point_unite_2];
                
                var hot_point_libelle_3 = $('#libelle_hot_point_3').val();                
                var hot_point_value_3 = $('#value_hot_point_3').val();
                var hot_point_unite_3 = $('#unite_hot_point_3').val();
                var hot_points_3 = [hot_point_value_3,hot_point_libelle_3, hot_point_unite_3];
                
                // Flush Existing Message
                $('#foool_dashbox_hot_points div.updated').each(function(){$(this).remove();});
                $('#foool_dashbox_hot_points div.error').each(function(){$(this).remove();});

                $.ajax({
                    type : "post",
                    dataType : "json",
                    url : foool_dashbox_hot_points_params.ajaxurl,
                    data : {
                        action: "foool_dashbox_hot_points_save",  
                        nonce: nonce,
                        hot_points_1:hot_points_1,
                        hot_points_2:hot_points_2,
                        hot_points_3:hot_points_3
                    },
                    beforeSend: function() { 
                        $('#foool_dashbox_hot_points span.spinner').css('visibility','visible'); 
                    },
                    success: function(response) { 
                        if (response.success === true){
                            $('#foool_dashbox_hot_points h4').before('<div class="updated">Les chiffres clés sont à jours !</div>');
                        } else if(response.success === false){
                            $('#foool_dashbox_hot_points h4').before('<div class="error">Une erreur est survenue</div>');
                        }
                    },
                    error: function(response){},
                    complete: function(){
                        $('#foool_dashbox_hot_points span.spinner').css('visibility','hidden');
                    }
                });   

            });
            
        });
        
        $(window).load(function(){
            //alert('Window Loaded');
        });
        
} )( jQuery );


