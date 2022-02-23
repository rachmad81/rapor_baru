<div>
	
	<table id="dt_fixed_col" class="table table-striped table-bordered table-condensed">
		<thead>
			<tr style="border-top: 1px solid #ccc">
				<th rowspan="2" width="1%">NO</th>
				<th rowspan="2" width="10%">SISWA</th>
				<th colspan="2" width="40%">PENGETAHUAN | KKM = <?php echo $kkm." | TMP = ".$temp." | < ".$kkm." = D | ".$kkm."-".$c." = C | ".$c."-".$a." = B | ".$a."-100 = A";?></th>
				<th colspan="2" width="15%">KETERAMPILAN</th>
			</tr>
			<tr style="border-top: 1px solid #ccc">
				<th width="2%">NA KI-3</th>
				<th>DESKRIPSI KI-3</th>
				<th width="2%">NA KI-4</th>
				<th>DESKRIPSI KI-4</th>
			</tr>
		</thead>
		<tbody>
			<?php
			for ($i=0; $i < count($tampil); $i++) { 
				$baris = $tampil[$i];
				?>
				<tr>
					<td>{!! $baris['id'] !!}</td>
					<td>{!! $baris['nama'] !!}</td>
					<td>{!! $baris['hurufk1'] !!}</td>
					<td>{!! $baris['catatan1'] !!}</td>
					<td>{!! $baris['hurufk2'] !!}</td>
					<td>{!! $baris['catatan2'] !!}</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>