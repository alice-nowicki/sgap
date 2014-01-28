<?  if ($this->session->userdata['profil']>2){ ?>
	<div id="navlinks">
<?= anchor('enseignant','Retour vers la vue Enseignant'); ?>
<?if (isset($nom)) echo '<h2>'.$nom.'</h2>'; ?>
	</div>
<? } ?>

<div id="cycle_matiere_eleve">
<div id="cycle_matiere">
<div class="cycles">
	<h3>Cycle</h3>
<ul>
	<? foreach ($cycles as $cycle){?>
		<li name='<?=$cycle["id"]?>'> <?
			$date =date_create_from_format("d/m/Y",$cycle['debut']);
			$timestamp = $date->getTimestamp(); 
			echo strftime( "%a %d/%m/%Y", $timestamp ); ?>
	<?}?>
</ul>
</div>

<div class="matieres">
	<h3>Matière</h3>
<ul>
	<? foreach ($matieres as $matiere){ 
		$class = ($matiere['type']==1 ) ? 'perso' : 'rencontre'
	?>
		<li name='<?=$matiere["id"]?>' class='<?=$class?>'> <?=$matiere['nom']?>
	<?}?>
</ul>
</div>


<div class="infos">
	<h3>Informations</h3>
<ul>
<li> Nombre de places : <span id='nbPlaces'></span> </li>
<li> Nombre d'inscrits : <span id='nbInscrits'></span> </li>
<li> Salle : <span id='salle'></span> </li>
<li> Type : <span id='type'></span><li>
<li> Horaire : <span id='horaire'></span> </li>	
<li> Dates : <span id='dates'></span> </li>
<li> <center>
	<? if  ($this->session->userdata['profil']> 3) { 
		echo form_open('admin/inscription',array('id' => 'inscriptionForm')); 
	} else {
		echo form_open('eleve/inscription',array('id' => 'inscriptionForm')); 
	}	?>	
<input type='hidden' name='matiere_id' value=''>
<input type='hidden' name='cycle_id' value=''>
<input type='hidden' name='eleve_id' value=<?=$eleve_id?> >
<? if ($this->session->userdata['profil']> 3) { ?>
	<button type='submit' name='inscription' disabled >Forcer inscription</button>
<?} else { ?>
<button type='submit' name='inscription' disabled onclick="return confirm('Les inscriptions ne peuvent être annulées que par les professeurs. Êtes-vous sûr de vouloir cette inscription ?')" >Demander inscription</button>
<? } ?>
</form></center>
</li>
</ul>
</div>
</div>

</div>



