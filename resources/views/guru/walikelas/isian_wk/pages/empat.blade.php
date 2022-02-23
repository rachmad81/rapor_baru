<div>
	<table id="dt_fixed_col" class="table table-striped table-bordered table-condensed">
		<thead>
			<tr style="border-top: 1px solid #ccc">
				<th rowspan="2">NO</th>	
				<th rowspan="2">SISWA</th>	
				<th colspan="2">SPIRITUAL (KI-1)</th>
				<th colspan="2">SOSIAL (KI-2)</th>
			</tr>
			<tr style="border-top: 1px solid #ccc">
				<th>NILAI</th>
				<th>CATATAN</th>
				<th>NILAI</th>
				<th>CATATAN</th>
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