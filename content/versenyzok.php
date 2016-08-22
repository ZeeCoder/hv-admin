
<div class="black_cloud centering_parent">
	<div class="form_aligner centered_child">
		<form class="centered_child" id="upload_form">
			<!-- <div class="info">Az aktuális kérdés a dátum alapján automatikusan adódik.</div> -->
			
			<input type="text" placeholder="Név" name="name" id="name" />
			<!-- <input type="text" placeholder="Kor" name="age" id="age" /> -->
			<!-- <input type="text" placeholder="Város" name="city" id="city" /> -->
			Aktív: <input type="checkbox" value="1" id="active" />

			<div id="pictures" class="pictures">
				<img src="" alt="">
			</div>
			
		</form>

		<form action="call/upload_file.php" id="file_upload" enctype="multipart/form-data" method="post">
			<input type="file" name="file" />
			<input type="hidden" name="cmd" value="upload_contestant" />
			<input type="hidden" name="id" id="id" value="" />
		</form>
		
		<input id="submitbtn" type="submit" value="Feltöltés" />
	</div>
</div>

<!-- 
<ul class="states" style="margin-top: 20px;">
	<li class="warning">A fájlok feltöltésének maximális mérete: <?php echo str_replace('M', ' Megabájt', ini_get('upload_max_filesize')); ?>.</li>
</ul>
-->

<div class="new_record" onclick="new_record()">Új versenyző felvitele</div>

<table class="datatable allusers">
	<thead>
		<tr>
			<td class='name'>Név</td>
			<td class='date small'>Aktív</td>
			<td class='date small'>Felvitel</td>
			<td class='mod'>Módosít</td>
			<td class='del'>Töröl</td>
		</tr>
	</thead>
	<tbody>
		<!-- Generated content -->
	</tbody>
</table>
