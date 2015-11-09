/** personalizacion IbericaSoft 2014 */
$(document).ready(function(){
	//slideshow
    slideshow({
      rootElement: 'slideshow',
      autoplayInterval: 10000,
      autoplay: true
   });  
    
   //dialogo mellamais
   $('.mellamais').click(function() {  
	   	mostrar_centrar_mask($(this));
    	$("#teloemail").focus();
    	return false;
   });
   
   //When clicking on the button close or the mask layer the popup closed
   $('a.close, #mask').on('click', function() { 
     $('#mask , .form-popup').fadeOut(300 , function() {
       $('#mask').remove();  
       return false;
     });
   });  
   
   //dialogo politica de privacidad
   $('.politica').click(function() {
	   mostrar_centrar_mask($(this));
	   return false;
   });
   
 
});

function mostrar_centrar_mask(item){
	//Getting the variable's value from a link 
	var box = $(item).attr('href');

	//Fade in the Popup
	$(box).fadeIn(300);
	
	$(box).css({top:'50%',left:'50%',margin:'-'+($(box).height() / 2)+'px 0 0 -'+($(box).width() / 2)+'px'});
	
	// Add the mask to body
	$('body').append('<div id="mask"></div>');
	$('#mask').fadeIn(300);
}