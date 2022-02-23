<div class="row">
	<div class="col-3 col-sm-3">
		<div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
			<a href="javascript:void(0)" class="nav-link active-tab3" id="tab3-1" onclick="pages3('1')">Jujur</a>
			<a href="javascript:void(0)" class="nav-link active-tab3" id="tab3-2" onclick="pages3('2')">Disiplin</a>
			<a href="javascript:void(0)" class="nav-link active-tab3" id="tab3-3" onclick="pages3('3')">Tanggung jawab</a>
			<a href="javascript:void(0)" class="nav-link active-tab3" id="tab3-4" onclick="pages3('4')">Sopan Santun</a>
			<a href="javascript:void(0)" class="nav-link active-tab3" id="tab3-5" onclick="pages3('5')">Percaya Diri</a>
			<a href="javascript:void(0)" class="nav-link active-tab3" id="tab3-6" onclick="pages3('6')">Peduli</a>
			@if(Session::get('jenjang')=='SMP')
			<a href="javascript:void(0)" class="nav-link active-tab3" id="tab3-7" onclick="pages3('7')">Kerja Sama</a>
			@endif
		</div>
	</div>
	<div class="col-9 col-sm-9">
		<div class="overlay1">
			<i class="fas fa-3x fa-sync-alt fa-spin"></i>
			<div class="text-bold pt-2">Loading...</div>
		</div>
		<div id="pages3" style="overflow: auto">
			
		</div>
		
	</div>
</div>