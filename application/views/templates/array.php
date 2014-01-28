<?  if ($this->session->userdata['profil']>3){ ?>
	<div id="navlinks">
<?= anchor('admin','Retour Vue Administrateur'); ?>
	</div>
<? } else { ?>
	<div id="navlinks">
<?= anchor('enseignant','Retour Vue Enseignent'); ?>
	</div>
<? }?>

<? if (isset($title) ) { ?>
	<br/><br/>
<h3> <?=$title?> </h3>
<? }?>
<table class="bordered tablesorter">
    <thead>
    <tr>
		<?
		$keys = array_keys($json[0]);
		foreach ($keys as $key ) echo "<th>".ucfirst($key)."</th>";
		?>
    </tr>
    </thead>
 
    <tbody>
            <?php foreach($json as $field){?>
                <tr>
					<?
					foreach ($keys as $key ) echo "<td>".$field[$key]."</td>";
					?>
                </tr>
            <?php }?>
    </tbody>
 
</table>

<script>
$(document).ready(function() {
	$('.tablesorter').tablesorter()
})
</script>