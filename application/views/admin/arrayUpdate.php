<center>
<?= anchor('admin/cancelUpload','Annuler la mise à Jour');?><br/>
<?= anchor('admin/confirm/'.$type,'Confirmer la mise à Jour');?> - ! Irréversible !
</center>
<? function table($json) { ?>
<? if (count($json)==0) {echo "Aucun"; return;} ?>
<table class="bordered">
    <thead>
    <tr>
		<?
		$keys = array_keys($json[0]);
		foreach ($keys as $key ) echo "<th>$key</th>";
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
<?}?>
<h2>Ajouts</h2>
<?table($ajouts);?>
<h2>Actifs</h2>
<?table($modifications);?>
<h2>Inactifs</h2>
<?table($desactive);?>

<!--
<h2>Erreurs</h2>
<?table($errors);?>
-->