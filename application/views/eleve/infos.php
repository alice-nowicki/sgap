<h2>Liste des inscriptions</h2>
<div class="historique">
<table class="bordered">
	<thead>
		<tr><th>Date début</th><th>Matière</th><th>Commentaire de groupe</th><th>Commentaire personnalisé</th></tr>
	</thead>
	<tbody>
	<? foreach ($historiqueAccompagnements as $historique){?>
		<tr><td>
			<?=datefr($historique["cycle_debut"])?>
		</td><td><?=$historique['matiere_nom']?></td>
		<td><?=$historique['commentaire_general']?></td>
		<td><?=$historique['commentaire_perso']?></td>
	</tr>
	<?}?>
	</tbody>
</table>
</div>

<? if (count($historiqueSeances) >= 1)  { ?>
<h2>Absences</h2>
<div class="historique_presence">
	<table class="bordered">
		<thead>
			<tr><!-- <th>Cycle</th> --><th>Matière</th><th>Date</th></tr>
		</thead>
		<tbody>
	<? foreach ($historiqueSeances as $historique){?>
			<tr>
				<!-- <td><?=$historique['cycle_debut']?></td> -->
			<td><?=$historique['matiere_nom']?></td>
			<td><?=$historique['seance_date']?></td>
		</tr>
	<?}?>

		</tbody>
	</table>
</div>
<? } else { ?>
	<p>Pas d'absences.</p>
<? }?>