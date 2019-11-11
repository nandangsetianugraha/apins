<?php 
require_once '../../inc/db_connect.php';
$tapel=$_GET['tapel'];
$smt=$_GET['smt'];
$kelas=$_GET['kelas'];
$ab=substr($kelas,0,1);
$peta=$_GET['peta'];
$mpid=$_GET['mp'];
$kd=$_GET['kd'];
$tema=$_GET['tema'];
$ada=0;
$sql11="select * from kd where kelas='$ab' and mapel='$mpid' group by kd";
$query11 = $connect->query($sql11);
while($h=$query11->fetch_assoc()) {
    $kdn=$h['kd'];
    $ckkm1=$connect->query("select * from kkmku where kelas='$ab' and tapel='$tapel' and mapel='$mpid' and kd='$kdn' and jenis='1'")->num_rows;
    $ckkm2=$connect->query("select * from kkmku where kelas='$ab' and tapel='$tapel' and mapel='$mpid' and kd='$kdn' and jenis='2'")->num_rows;
    $ckkm3=$connect->query("select * from kkmku where kelas='$ab' and tapel='$tapel' and mapel='$mpid' and kd='$kdn' and jenis='3'")->num_rows;
    if($ckkm1>0){$ada=$ada;}else{$ada=$ada+1;};
    if($ckkm2>0){$ada=$ada;}else{$ada=$ada+1;};
    if($ckkm3>0){$ada=$ada;}else{$ada=$ada+1;};
};
if($ada>0){
	$boleh=false;
}else{
	$boleh=true;
};
$mpm=$connect->query("select * from mapel where id_mapel='$mpid'")->fetch_assoc();
if($kd==0){
	echo "<div class='alert alert-info alert-dismissible'><h4><i class='icon fa fa-info'></i> Informasi</h4>Silahkan Pilih Kompetensi Dasar (KD)</div>";
}else{
	if($boleh==false){
		?>
		<div class="error-page">
			<div class="error-content text-center" style="margin-left: 0;">
				<h3><i class="fa fa-info-circle text-danger"></i> Kesalahan </h3>
				<p>Belum Mengisi KKM <?=$mpm['nama_mapel'];?> Kelas <?=$ab;?>, silahkan isi terlebih dahulu dan lengkapi KKM <?=$mpm['nama_mapel'];?> Kelas <?=$ab;?>.</p>
			</div>
		</div>
	<?php 
	}else{	
		?>

		<div class="table-responsive no-padding">
		<table class="table table-bordered table-hover">
									<thead>
									   <tr>
										<th>Nama Siswa</th>
											<th>Ulangan</th>
											<th>Tugas 1</th>
											<th>Tugas 2</th>
										</tr>
									</thead>
									<tbody>	
		<?php 
		$sql="select * from penempatan where rombel='$kelas' and tapel='$tapel' order by nama asc";
		$query = $connect->query($sql);
		while($s=$query->fetch_assoc()) {
			$idp=$s['peserta_didik_id'];
			$sql1 = "select * from nh where id_pd='$idp' and smt='$smt' and tapel='$tapel' and mapel='$mpid' and tema='$tema' and kd='$kd' and jns='tls'";
			$nh = $connect->query($sql1);
			$sql2 = "select * from nh where id_pd='$idp' and smt='$smt' and tapel='$tapel' and mapel='$mpid' and tema='$tema' and kd='$kd' and jns='tgs1'";
			$nh1 = $connect->query($sql2);
			$sql3 = "select * from nh where id_pd='$idp' and smt='$smt' and tapel='$tapel' and mapel='$mpid' and tema='$tema' and kd='$kd' and jns='lsn'";
			$nh2 = $connect->query($sql3);
			$m=$nh->fetch_assoc();
			$m1=$nh1->fetch_assoc();
			$m2=$nh2->fetch_assoc();
			if(empty($m['nilai'])){
				$nHar='';
			}else{
				$nHar=number_format($m['nilai'],0);
			};
			if(empty($m1['nilai'])){
				$ntgs='';
			}else{
				$ntgs=number_format($m1['nilai'],0);
			};
			if(empty($m2['nilai'])){
				$nlsn='';
			}else{
				$nlsn=number_format($m2['nilai'],0);
			};
			$nh='
				<span class="input-group-addon" contenteditable="true" data-old_value="'.$nHar.'"  onBlur="saveHarian(this,\'nilai\',\''.$idp.'\',\''.$ab.'\',\''.$smt.'\',\''.$tapel.'\',\''.$mpid.'\',\''.$kd.'\',\'tls\',\''.$tema.'\')" onClick="highlightEdit(this);">'.$nHar.'</span>
			';
			$ntg1='
				<span class="input-group-addon" contenteditable="true" data-old_value="'.$ntgs.'"  onBlur="saveHarian(this,\'nilai\',\''.$idp.'\',\''.$ab.'\',\''.$smt.'\',\''.$tapel.'\',\''.$mpid.'\',\''.$kd.'\',\'tgs1\',\''.$tema.'\')" onClick="highlightEdit(this);">'.$ntgs.'</span>
			';
			$ntg2='
				<span class="input-group-addon" contenteditable="true" data-old_value="'.$nlsn.'"  onBlur="saveHarian(this,\'nilai\',\''.$idp.'\',\''.$ab.'\',\''.$smt.'\',\''.$tapel.'\',\''.$mpid.'\',\''.$kd.'\',\'lsn\',\''.$tema.'\')" onClick="highlightEdit(this);">'.$nlsn.'</span>
			';
		?>
		<tr>
			<td><?=$s['nama'];?></td>
			<td><?=$nh;?></td>
			<td><?=$ntg1;?></td>
			<td><?=$ntg2;?></td>
		</tr>
		<?php
		};
		?>
																	
									</tbody>
								</table>
								</div>
<?php };};?>