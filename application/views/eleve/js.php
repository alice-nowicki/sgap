<script type='text/javascript'>
var accompagnement=<?=json_encode( $accompagnement )?>	

function unactivateMatieres(cycle_id){
	//$(".cycles ul li").removeClass('unactivated')
	$(".matieres ul li").addClass('unactivated')
	for (var i in accompagnement) {
		if (accompagnement[i].cycle_id == cycle_id) {
			matiere_id=accompagnement[i].matiere_id
			$('.matieres ul li[name="'+matiere_id+'"]').removeClass('unactivated')
		} 
	}
}

function unactivateCycles(matiere_id){
	//$(".matieres ul li").removeClass('unactivated')
	$(".cycles ul li").addClass('unactivated')
	for (var i in accompagnement) {
		if (accompagnement[i].matiere_id == matiere_id) {
			cycle_id=accompagnement[i].cycle_id
			$('.cycles ul li[name="'+cycle_id+'"]').removeClass('unactivated')
		} 
	}
}

function activateSuscribe(){
	if (($('.cycles .highlight').length == 1) && ($('.matieres .highlight').length == 1)) {
		$('#inscriptionForm button[name="inscription"]').prop("disabled", false);;
		cycle_id=$('#inscriptionForm input[name="cycle_id"]').val();
		matiere_id=$('#inscriptionForm input[name="matiere_id"]').val();
		myurl = '<?=site_url()?>/inscription/getNbPlacesRestantes/'+cycle_id+'/'+matiere_id;
		$.ajax({
			url:myurl, 
			context: document.body 
		}).done(function(data) {
			$('#salle').html(data.salle);
			$('#dates').html(data.dates.join(', <br/>'));
			$('#nbPlaces').html(data.places);
			$('#horaire').html(data.horaire);
			$('#type').html(data.type);
			$('#nbInscrits').html(data.nb_inscrits);
			if (isNaN(data.places)) $('#inscriptionForm button[name="inscription"]').prop("disabled", true);
			$('#inscriptionForm button[name="inscription"]').text("Demander inscription")
			<? if ($this->session->userdata['profil']<= 3) { ?>
			if (data.type=="Rencontre") {
				$('#inscriptionForm button[name="inscription"]').prop("disabled", true);
				$('#inscriptionForm button[name="inscription"]').text("Inscription par Professeurs")
			}
			if (data.places<=data.nb_inscrits) { 
				$('#inscriptionForm button[name="inscription"]').prop("disabled", true);
				$('#inscriptionForm button[name="inscription"]').text("Plus de places")
			}
			<? } else { ?>
				$('#inscriptionForm button[name="inscription"]').text("Forcer inscription")
			<? }?>
			if (!data.logged) window.location.reload();
		});
			
	}
}
	
$(document).ready(function() {
		
	$(".cycles ul li").click(function(){
		$('.cycles .highlight').removeClass('highlight')
		$(this).toggleClass('highlight')
		var cycle_id=$(this).attr('name')
		$('#inscriptionForm input[name="cycle_id"]').val(cycle_id)
		unactivateMatieres(cycle_id)
		activateSuscribe()
	})
	
	$(".matieres ul li").click(function(){
		$('.matieres .highlight').removeClass('highlight')
		$(this).toggleClass('highlight')
		var matiere_id=$(this).attr('name')
		$('#inscriptionForm input[name="matiere_id"]').val(matiere_id)
		unactivateCycles(matiere_id)
		activateSuscribe()
	})
	
	
});
</script>



