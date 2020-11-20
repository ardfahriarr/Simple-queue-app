<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<link rel="stylesheet" type="text/css" href="assets\css\bootstrap.css">



<?php 
    include 'konek.php';
    if (isset($_GET['passpage'])) {
        $selectqueue = "SELECT * FROM tabel_antrian WHERE keterangan = 'BELUM'";
        $getqueue = $koneksi->query($selectqueue);
        $countqueue = $getqueue->num_rows;
    
        $last = "SELECT * FROM tabel_antrian ORDER BY id DESC LIMIT 1";
        $getlastqueue = $koneksi->query($last);
        if ($row = $getlastqueue->fetch_assoc()) {
            $countdata = $row['hitung'];
            $lastdate = $row['tanggal'];
        } else {
            $lastdate = 0;
        }
        
        date_default_timezone_set('Asia/Jakarta');
        $currentdate = date('Ymd');
        if ($currentdate == $lastdate) {
            $countdata;
        } else {
            $countdata = 0;
        }
        $hitung = $countdata + 1;
        $keterangan = "BELUM";
        $coba = "
        <html>
            <center>
                <br><div style='border: 3px solid black; width: 250px;'>
                <div>SISTEM ANTRIAN KANTOR<br>PERIZINAN KABUPATEN<br>KOTAWARINGIN BARAT</div>
                    <h1>".$hitung."</h1>
                </div>
            </center>
        </html>";
        echo $coba;
        $addqueue = "INSERT INTO tabel_antrian (hitung, keterangan, tanggal) VALUES ('$hitung', '$keterangan', '$currentdate')";
        $insert = $koneksi->query($addqueue);
        if ($insert) {
            if ($countqueue < 1) {
                ?> 
                    <script>
                        window.print();
                        window.onafterprint = function(){
                          setTimeout(function () {
                              Swal.fire({
                                  title: 'Berhasil',
                                  text: 'Anda adalah antrian pertama.',
                                  type: 'success',
                                  confirmButtonColor: '#3085d6',
                                  confirmButtonText: 'Okay!'
                                  }).then((result) => {
                                      if (result.value) {
                                          window.location = 'index.php';
                                      }
                                  });
                          }, 100);
                        }
                    </script> 
                <?php  
            } else {
                ?> 
                    <script>
                        window.print();
                        window.onafterprint = function(){
                          var sisa = <?php echo $countqueue; ?>+1;
                          setTimeout(function () {
                              Swal.fire({
                                  title: 'Berhasil',
                                  text: 'Silahkan menunggu sebanyak '+sisa+' antrian lagi.',
                                  type: 'success',
                                  confirmButtonColor: '#3085d6',
                                  confirmButtonText: 'Okay!'
                                  }).then((result) => {
                                      if (result.value) {
                                        window.location = 'index.php';
                                      }
                                  });
                          }, 100);
                        }
                    </script> 
                <?php  
            }
        }
        else{
            echo "gagal".$koneksi->error;
        }
    } else {
      ?>
          <!DOCTYPE html>
          <html lang="en">
            <head>
              <title>Hello, world!</title>
            </head>
            <body>
            
            <div class="container">
              <center>
                  <h1 class="mt-3 mb-5">
                      SISTEM ANTRIAN KANTOR PERIZINAN <br>
                      KABUPATEN KOTAWARINGIN BARAT
                  </h1>
              </center>
              

              <div class="row">
                  <div class='col-5'>
                      <center class='mt-5'>
                          <img style="width:250px;" src="pemkab.png">
                      </center>
                  </div>
                  <div class='col-6'>
                      <center class='mt-5'><br>
                          <a class="mt-5 btn btn-primary" href="index.php?passpage=1"><h1>CETAK NOMOR ANTRIAN</h1></a>
                      </center>
                  </div>
              </div>
            </div>
            </body>
          </html>
      <?php  
    }
?>






