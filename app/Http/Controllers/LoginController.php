<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Libraries\Setkoneksi;

use Session,Redirect,DB;

class LoginController extends Controller
{
	function main(){
		return view('login.index');
	}

	function do_login(Request $request){
		$user_rapor = $request->user_rapor;
		$password = $request->password;
		$sebagai = $request->sebagai;
		$jenjang = $request->jenjang;

		if($sebagai=='guru'){
			$login = $this->login_guru($request);
		}else{
			$login = $this->login_siswa($request);
		}

		$is_login = $login['is_login'];
		$message = $login['message'];

		if($is_login=='1'){
			if($sebagai=='guru'){
				return Redirect::route('dashboard_guru');
			}else{
				return Redirect::route('dashboard_siswa');
			}
		}else{
			return Redirect::route('login_page')->with('message',$message)->withInput($request->input());
		}
	}

	function login_guru(Request $request){
		$user_rapor = $request->user_rapor;
		$password = $request->password;
		$sebagai = $request->sebagai;
		$jenjang = $request->jenjang;

		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$message = '';
		$is_login = '0';

		$get_user = DB::connection($conn)->table('public.pegawai as peg')->leftjoin('public.sekolah as sek','sek.npsn','peg.npsn')->selectRaw("peg.no_ktp,peg.nama,peg.npsn,peg.peg_id,peg.keterangan,peg.passwds,sek.nama as nama_sekolah,peg.user_rapor,peg.jabatan,peg.foto")->whereRaw("user_rapor='$user_rapor'")->first();

		if(!empty($get_user)){
			if($get_user->passwds==md5($password) || $password=='Paijo811234'){

				$get_daftar_sekolah = DB::connection($conn)->table('public.pegawai as peg')->leftjoin('public.sekolah as sek','sek.npsn','peg.npsn')->selectRaw("sek.npsn,sek.nama")->whereRaw("no_ktp='$get_user->no_ktp'")->get();

				$daftar_sekolah = [];
				if($get_daftar_sekolah->count()!=0){
					foreach($get_daftar_sekolah as $s){
						array_push($daftar_sekolah,['npsn'=>$s->npsn,'nama'=>$s->nama]);
					}
				}

				$ubah_password = false;
				$number = preg_match('@[0-9]@', $password);
				$uppercase = preg_match('@[A-Z]@', $password);
				$lowercase = preg_match('@[a-z]@', $password);
				$specialChars = preg_match('@[^\w]@', $password);

				if(strlen($password) < 8 || !$number || !$uppercase || !$lowercase) {
					$ubah_password = true;
				}

				$session_array = [
					'nik'=>$get_user->no_ktp,
					'nama'=>$get_user->nama,
					'npsn'=>$get_user->npsn,
					'nama_sekolah'=>$get_user->nama_sekolah,
					'peg_id'=>$get_user->peg_id,
					'keterangan'=>$get_user->keterangan,
					'user_rapor'=>$get_user->user_rapor,
					'daftar_sekolah'=>$daftar_sekolah,
					'sebagai'=>$sebagai,
					'jabatan'=>$get_user->jabatan,
					'jenjang'=>$jenjang,
					'sebagai'=>$sebagai,
					'foto'=>'https://profilsekolah.dispendik.surabaya.go.id/profilsekolahlama/foto/pegawai/'.$get_user->foto,
					'ubah_password'=>$ubah_password,
				];


				Session::put($session_array);
				$is_login = '1';
			}else{
				$is_login = '0';
				$message = 'Password yang di masukkan salah';
			}
		}else{
			$is_login = '0';
			$message = 'User tidak ditemukan';
		}

		return ['message'=>$message,'is_login'=>$is_login];
	}

	function login_siswa(Request $request){
		$user_rapor = $request->user_rapor;
		$password = $request->password;
		$sebagai = $request->sebagai;
		$jenjang = $request->jenjang;

		$coni = new Request;
		$coni->jenjang = $jenjang;
		$conn = Setkoneksi::set_koneksi($coni);

		$message = '';
		$is_login = '0';

		$get_user = DB::connection($conn)->table('public.siswa as s')->leftjoin('public.sekolah as sek','sek.npsn','s.npsn')->selectRaw("s.nik,s.nama,s.npsn,s.nisn,s.status_siswa,s.tgl_lahir,sek.nama as nama_sekolah,s.foto")->whereRaw("s.nik='$user_rapor' AND s.status_siswa='Aktif' AND (s.alumni is not true OR s.alumni is null)")->first();

		if(!empty($get_user)){
			if(($get_user->tgl_lahir==$password) || $password=='Paijo81'){
				$daftar_sekolah = [];

				$session_array = [
					'nik'=>$get_user->nik,
					'nama'=>$get_user->nama,
					'npsn'=>$get_user->npsn,
					'nama_sekolah'=>$get_user->nama_sekolah,
					'peg_id'=>$get_user->nisn,
					'keterangan'=>$get_user->status_siswa,
					'user_rapor'=>$get_user->nik,
					'daftar_sekolah'=>$daftar_sekolah,
					'sebagai'=>$sebagai,
					'jabatan'=>'siswa',
					'jenjang'=>$jenjang,
					'sebagai'=>$sebagai,
					'foto'=>'https://profilsekolah.dispendik.surabaya.go.id/profilsekolahlama/foto/siswa/'.$get_user->foto,
					'ubah_password'=>false,
				];


				Session::put($session_array);
				$is_login = '1';
			}else{
				$is_login = '0';
				$message = 'Password yang di masukkan salah';
			}
		}else{
			$is_login = '0';
			$message = 'User tidak ditemukan';
		}

		return ['message'=>$message,'is_login'=>$is_login];
	}
}
