<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="assets\js\jquery-3.3.1.min.js"></script>
<script src="assets\js\bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets\css\bootstrap.css">


<?php 
    include 'konek.php';
    $selectcurr = "SELECT * FROM tabel_antrian WHERE keterangan = 'SUDAH' ORDER BY id DESC LIMIT 1";
    $getcurr = $koneksi->query($selectcurr);
    $iscurrentdata = $getcurr->num_rows;
    if ($iscurrentdata > 0) {
      while ($row = $getcurr->fetch_assoc()) {
        $temp = $row['hitung'];
        $servednow = $temp; 
      }
    } else {
        $servednow = 0;
    }
    ?>
    <script>
      function panggil(param1,param2) {
          var asep = new Array(new Audio('suara/antrian_nomor.mp3'));
          var servednow = String(param1);
          countarr = servednow.split("");
          countarrlength = countarr.length;
          if (countarrlength == 1) {
            asep.push(new Audio('suara/'+countarr[0]+'.mp3'));
          } else if (countarrlength == 2) {
            if (countarr[0] == 1 && countarr[1] == 0) {
              asep.push(new Audio('suara/'+<?php echo $servednow; ?>+'.mp3'));
            } else if (countarr[0] == 1 && countarr[1] == 1) {
              asep.push(new Audio('suara/'+<?php echo $servednow; ?>+'.mp3'));        
            } else if (countarr[0] == 1) {
              asep.push(new Audio('suara/'+countarr[1]+'.mp3'));
              asep.push(new Audio('suara/belas.mp3'));
            } else {
              asep.push(new Audio('suara/'+countarr[0]+'.mp3'));
              asep.push(new Audio('suara/puluh.mp3'));
              if (countarr[1] > 0) {
                asep.push(new Audio('suara/'+countarr[1]+'.mp3'));
              }
            }
          }
          asep.push(new Audio('suara/silahkan_ke_loket.mp3'));
          var teler = String(param2);
          asep.push(new Audio('suara/'+teler+'.mp3'));
          var i = 0;
          playSnd();
          function playSnd() {
              if (i == asep.length) return;
              asep[i].addEventListener('ended', playSnd);
              asep[i].play();
              i++;
          }
      }
    </script>
    <?php 
    if (isset($_POST['submit'])) {
        $teler = $_POST['teler'];
        $selectall = "SELECT * FROM tabel_antrian WHERE keterangan = 'BELUM' LIMIT 1";
        $getdata = $koneksi->query($selectall);
        if ($row = $getdata->fetch_assoc()) {
            $getfirstitem = $row['hitung'];
        } else {
            $getfirstitem = 0;
        }
        ?> 
        <script> 
            var firstitem = <?php echo $getfirstitem; ?>;
            if (firstitem == 0) {
              setTimeout(function () {
                Swal.fire({
                  title: 'Belum ada antrian',
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Okay!'
                    }).then((result) => {
                        if (result.value) {
                            window.location = 'teller.php';
                        }
                    });
                }, 100);
            } else {
              panggil(<?php echo $getfirstitem; ?>,<?php echo $teler; ?>);
              var teler = <?php echo $teler; ?>;
              setTimeout(function () {
                  Swal.fire({
                      title: 'Antrian nomor '+firstitem,
                      text: 'Silahkan ke loket '+teler,
                      type: 'warning',
                      confirmButtonColor: '#3085d6',
                      confirmButtonText: 'Okay!'
                      }).then((result) => {
                          if (result.value) {
                              window.location = 'teller.php?coba='+teler+'';
                          }
                      });
              }, 100);
            }
        </script>
        <?php  
        $getitem = "UPDATE tabel_antrian SET keterangan = 'SUDAH' WHERE hitung = $getfirstitem";
        $update = $koneksi->query($getitem);
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
                <br><br>
                <div class='row'>
                  <div class='col-5'>
                      <center>
                        <img style="width:250px;" src="pemkab.png">
                      </center>
                  </div>
                  <div class='col-6 card'>
                      <div class='mt-4'>
                          <div class="form-group">
                              <!-- jumlah_antrian -->
                              <?php 
                                  $selectall = "SELECT * FROM tabel_antrian WHERE keterangan = 'BELUM'";
                                  $getdata = $koneksi->query($selectall);
                                  $countqueue = $getdata->num_rows;
                              ?>
                              <div class="form-group row">
                                <label for="jumlah_antrian" class="col-sm-4 col-form-label">
                                    <div class="float-right">
                                        Jumlah antrian
                                    </div>  
                                </label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" id="jumlah_antrian" value='<?= $countqueue ?>' readonly=readonly>
                                </div>
                              </div>
                              
                              <!-- antrian selanjutnya -->
                              <?php 
                                  $selectnext = "SELECT * FROM tabel_antrian WHERE keterangan = 'BELUM' LIMIT 1";
                                  $getnext = $koneksi->query($selectnext);
                                  $isnext = $getnext->num_rows;
                                  if ($isnext > 0) {
                                    while ($row = $getnext->fetch_assoc()) {
                                      $nextqueue = $row['hitung'];
                                    }
                                  } else {
                                    $nextqueue = 0;
                                  }
                              ?>
                              <div class="form-group row">
                                <label for="selanjutnya" class="col-sm-4 col-form-label">
                                    <div class="float-right">
                                        Antrian selanjutnya
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" id="selanjutnya" value='<?= $nextqueue ?>' readonly=readonly>
                                </div>
                              </div>

                              <!-- antrian yang dilayani -->
                              <div class="form-group row">
                                <label for="dilayanisekarang" class="col-sm-4 col-form-label">
                                    <div class="float-right">
                                        Antrian yang dilayani
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" id="dilayanisekarang" value='<?= $servednow ?>' readonly=readonly>
                                </div>
                              </div>
                              
                              <!-- pilih teller -->
                              <?php 
                                  if (isset($_GET['coba'])) {
                                    $teler = $_GET['coba'];
                                  } else {
                                    $teler = 1;
                                  }
                              ?>
                              <form action="" method='POST'>
                                  <div class="form-group row">
                                      <label for="pilihteller" class="col-sm-4 col-form-label">
                                          <div class="float-right">
                                              <b>Nomor teller</b>
                                          </div>
                                      </label>
                                      <div class="col-sm-8">
                                          <input type="text" class="form-control" id="pilihteller" name='teler' value='<?= $teler ?>'>
                                      </div>
                                  </div>
                                  <button class='float-right btn btn-success btn-lg' type='submit' name='submit'>ANTRIAN SELANJUTNYA</button>
                              </form>
                              <button onclick="panggil(<?= $servednow ?>,<?= $teler ?>)" class='mr-2 float-right btn btn-primary btn-lg'>PANGGIL ANTRIAN</button>
                          </div>
                      </div>
                </div>
            </div>
            </body>
          </html>
      <?php  
    }
?>

