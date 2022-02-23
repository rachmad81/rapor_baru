<div class="row">
	<div class="col-3 col-sm-3">
		<div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
			<a href="javascript:void(0)" class="nav-link active-tab2" id="tab2-1" onclick="pages2('1')">Ketaan Beribadah</a>
			<a href="javascript:void(0)" class="nav-link active-tab2" id="tab2-2" onclick="pages2('2')">Perilaku Bersyukur</a>
			@if(Session::get('jenjang')=='SD')
			<a href="javascript:void(0)" class="nav-link active-tab2" id="tab2-3" onclick="pages2('3')">Berdoa</a>
			<a href="javascript:void(0)" class="nav-link active-tab2" id="tab2-4" onclick="pages2('4')">Toleransi beribadah</a>
			@endif
		</div>
	</div>
	<div class="col-9 col-sm-9">
		<div class="overlay1">
			<i class="fas fa-3x fa-sync-alt fa-spin"></i>
			<div class="text-bold pt-2">Loading...</div>
		</div>
		<div id="pages2" style="overflow: auto">
			
		</div>
	</div>
</div>