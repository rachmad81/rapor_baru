<?php 
namespace App\Http\Libraries;

use Illuminate\Http\Request;
use Session;

class Setkoneksi
{
	public static function set_koneksi(Request $request){
		$jenjang = $request->jenjang;

		if($jenjang=='SD'){
			$database = 'pgsql_sd';
		}else{
			$database = 'pgsql_smp';
		}

		return $database;
	}

	public static function tahun_ajaran(){
		$jenjang = Session::get('jenjang');

		if($jenjang=='SD'){
			$nama_schema = 'sd';
		}else{
			$nama_schema = 'smp';
		}
		$tahun_ajaran = [
			[
				'nama'=>'Tahun ajaran 2013/2014 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2013_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2013/2014 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2013_genap',
			],
			[
				'nama'=>'Tahun ajaran 2014/2015 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2014_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2014/2015 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2014_genap',
			],
			[
				'nama'=>'Tahun ajaran 2015/2016 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2015_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2015/2016 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2015_genap',
			],
			[
				'nama'=>'Tahun ajaran 2016/2017 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2016_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2016/2017 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2016_genap',
			],
			[
				'nama'=>'Tahun ajaran 2017/2018 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2017_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2017/2018 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2017_genap',
			],
			[
				'nama'=>'Tahun ajaran 2018/2019 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2018_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2018/2019 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2018_genap',
			],
			[
				'nama'=>'Tahun ajaran 2019/2020 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2019_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2019/2020 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2019_genap',
			],
			[
				'nama'=>'Tahun ajaran 2020/2021 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2020_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2020/2021 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2020_genap',
			],
			[
				'nama'=>'Tahun ajaran 2021/2022 Ganjil',
				'nilai'=>'rapor_'.$nama_schema.'_2021_ganjil',
			],
			[
				'nama'=>'Tahun ajaran 2021/2022 Genap',
				'nilai'=>'rapor_'.$nama_schema.'_2021_genap',
			],
		];

		return $tahun_ajaran;
	}
}
?>