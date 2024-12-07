<?php
session_start();
$idTest = null;
if (isset($_SESSION['user']['student_id'])) {
  
  $idTest = $_SESSION['user']['student_id'];
}
$account_number = $_GET['account_number'];
include './../../../src/modules/database.php';

$db = (new Database())->getConnection();
$result = $db->execute_query("SELECT 
    A.person_id,
    A.account_number,
    A.institute_email,
    A.direction,
    A.photos,
    B.first_name,
    B.last_name,
    B.phone,
    B.personal_email,
    C.career_name,
    F.faculty_name,
    rc.center_name
FROM Students A 
    LEFT JOIN Persons B ON A.person_id = B.person_id 
    LEFT JOIN Careers C ON A.career_id = C.career_id
    LEFT JOIN Faculty F ON C.faculty_id = F.faculty_id
    LEFT JOIN Regional_center rc ON B.center_id = rc.center_id
WHERE A.account_number = ?;", [$account_number]);
$student = $result->fetch_assoc() ?? null;
$result = $db->execute_query("SELECT 
  CONCAT(s.hour_start, ' ', c.class_name) as class
FROM History h
INNER JOIN Section s ON h.section_id = s.section_id
INNER JOIN Classes c ON s.class_id = c.class_id
WHERE h.student_id = ?
", [$account_number]);


if (is_null($student)) {
  echo 'User not found!';
  return;
}

$isMyUser = $idTest == $account_number;

$index = $db->execute_query("SELECT 
    SUM(h.score * c.uv) / SUM(c.uv) AS indice_global,
    (SELECT 
        SUM(h1.score * c1.uv) / SUM(c1.uv)  
     FROM 
        History h1
     JOIN 
        `Section` s1 ON h1.section_id = s1.section_id
     JOIN 
        `Classes` c1 ON s1.class_id = c1.class_id
     JOIN 
        `Periods` p1 ON s1.period_id = p1.period_id
     WHERE 
        h1.student_id = h.student_id
        AND p1.active = 0 
        AND s1.period_id = (
            SELECT MAX(period_id)  
            FROM `Periods`
            WHERE active = 0
        )
    ) AS indice_ultimo_periodo
    FROM 
        History h
    JOIN 
        `Section` s ON h.section_id = s.section_id
    JOIN 
        `Classes` c ON s.class_id = c.class_id
    WHERE 
        h.student_id = ?
    GROUP BY 
        h.student_id;
    ", [$account_number]);
$indexRow = $index->fetch_assoc();

@$indiceGlobal = intval($indexRow['indice_global']);
@$indiceUltimoPeriodo = intval($indexRow['indice_ultimo_periodo']); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="cofloresf@unah.hn">
    <meta name="version" content="0.1.0">
    <meta name="date" content="02/11/2014">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
      input {
        width: 100%;
      }
    </style>
</head>
<body>

<!-- Modal Upload Photo -->
<div class="modal fade" id="uploadPhotoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg">
      <div class="modal-header bg">
        <h5 class="modal-title text" id="staticBackdropLabel">Agregar foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="uploadForm" action="/api/post/students/photo.php" method="POST"  enctype="multipart/form-data">
          <?php echo '<input type="hidden" name="account_number" value="'.$student['account_number'].'">'; ?>
          <input type="text" name="photoUrl" placeholder="Ingresar URL de la foto">
          <p class="mt-3">o</p>
          <input type="file" name="upload" accept="image/*" id="upload">
          <button type="submit" id="uploadBtn" class="btn bg-custom-primary mt-3 text-white">Subir</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal upload photo -->

    <div class="container pt-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:history.back()">Mi campus</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perfil</li>
          </ol>
        </nav>
        <div class="main-body">
              <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                  <div class="card bg-aux text">
                    <div class="card-body">
                      <div class="d-flex flex-column align-items-center text-center">
                        <div class="w-full">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                            <?php

                              @$photos = json_decode($student['photos'] ?? "", true);
                              for ($i=0; $i < 3; $i++) { 
                                if ($i === 0) {
                                    echo '<div class="carousel-item active relative">';
                                } else {
                                    echo '<div class="carousel-item relative">';
                                }
                                if (isset($photos["$i"]) && $photos["$i"] != '') {
                                  echo '<img src="'.$photos["$i"].'" class="d-block w-100" alt="...">';
                                  if ($isMyUser) {
                                    echo "<form class='d-inline deletePhotoForm'>";
                                    echo '<input type="hidden" name="filename" value="'.$photos["$i"].'">';
                                    echo '<input type="hidden" name="account_number" value="'.$student['account_number'].'">';
                                    echo "<button name='delete' value='".$photos["$i"]."' class='btn btn-danger'><i class='bi bi-trash'></i></button>";
                                    echo '</form>';
                                  } 
                                  echo '</div>';
                                }else {
                                  if ($isMyUser) {
                                    echo '<img src="/public/images/upload.webp" class="d-block w-100" alt="...">';
                                    echo "<button class='btn btn-primary'  data-bs-toggle='modal' data-bs-target='#uploadPhotoModal'><i class='bi bi-plus'></i></button>";
                                  }else{
                                    echo '<img src="/uploads/default.jpg" class="d-block w-100" alt="...">';
                                    
                                  }
                                  echo '</div>';

                                }
                              }
                            ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        </div>
                        <div class="mt-3">
                          <h4><?php echo $student['first_name'].' '.$student['last_name'] ?></h4>
                          <p class="text-secondary mb-1"><?php echo $student['career_name'] ?></p>
                          <p class="text-secondary text-xs mb-1"><?php echo $student['faculty_name'] ?></p>
                          <p class="text-secondary text-xs mb-1"><?php echo $student['center_name'] ?></p>
                          <?php
                            if (!$isMyUser) {
                              echo '<button class="btn btn-primary mr-1">Agregar a contactos</button>
                              
                              <a href="/views/chats/new.php?id='.$student['account_number'].'"><button class="btn btn-outline-primary mx-2">Enviar mensaje</button></a>';
                            }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                 
                </div>
                <div class="col-md-8">
                  <div class="card bg-aux mb-3">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Numero de cuenta</h6>
                        </div>
                        <div class="col text-secondary">
                            <input type="hidden" readonly data-key="person_id" value="<?php echo $student['person_id'] ?>">
                            <input type="text" readonly data-key="account_number" value="<?php echo $account_number ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Nombre completo</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly data-key="fullName" value="<?php echo $student['first_name'].' '.$student['last_name'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Correo personal</h6>
                        </div>
                        <div class="col text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="personalEmail" value="<?php echo $student['personal_email'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Correo institucional</h6>
                        </div>
                        <div class="col text-secondary">
                            <input type="text" readonly  data-key="email" value="<?php echo $student['institute_email'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Teléfono</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="phone" value="<?php echo $student['phone'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-3">
                          <h6 class="mb-0">Dirección</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <input type="text" readonly class="readonly-input"  data-key="direction" value="<?php echo $student['direction'] ?>">
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-sm-12" id="buttons-group">
                          <?php
                            if ($isMyUser) {
                              echo '<a id="btnEditProfile" class="btn btn-info" target="__blank">Edit</a> 
                              <button id="cancelButton" class="btn text-white btn-danger hidden">Cancel</button> 
                              <button id="saveButton" class="btn text-white btn-primary hidden">Save</button>
                              
                              <button id="LoadingButton" class="btn btn-primary hidden" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                              </button>';
                              
                            }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
    
                  <div class="row gutters-sm">
                    <div class="col-sm-6 mb-3">
                      <div class="card bg-aux h-100">
                        <div class="card-body">
                          <h6 class="d-flex align-items-center mb-3">
                            <i class="material-icons text-info mr-2"></i>Historial</h6>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                              echo '<p class="text-secondary mb-1">'.$row['class'].'</p>';
                            }


                            ?>
                          
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 mb-3 col-12">
                      <div class="row">
                        <div class="col-12 col-md-6">
                          <div class="card bg-aux h-100">
                            <div class="card-body">
                              <h6 class="d-flex align-items-center mb-3">Indice Global</h6>
                              <?php echo $indiceGlobal.'%'; ?>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="card bg-aux h-100">
                            <div class="card-body">
                              <h6 class="d-flex align-items-center mb-3">Indice Ultimo periodo</h6>
                              <?php echo $indiceUltimoPeriodo.'%'; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
    
    
    
                </div>
              </div>
    
            </div>
        </div>
    </div>
    
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

    <script>
      let buttonsGroup = document.getElementById('buttons-group'); 
      let btnEditProfile = document.getElementById('btnEditProfile'); 
      let saveButton = document.getElementById('saveButton'); 
      let cancelButton = document.getElementById('cancelButton'); 
      let LoadingButton = document.getElementById('LoadingButton'); 

  document.getElementById("uploadForm").addEventListener("submit", async function (event) {
    event.preventDefault();
    document.getElementById('uploadBtn').innerHTML = `<div class="spinner-border text-light" role="status"></div>`
    document.getElementById('uploadBtn').disabled = true;

    const form = event.target;
    const photoUrlInput = form.querySelector('input[name="photoUrl"]');
    const fileInput = form.querySelector('input[name="upload"]');
    const accountNumber = form.querySelector('input[name="account_number"]').value;

    const photoUrl = photoUrlInput.value.trim();
    const file = fileInput.files[0];

    let imageUrl;

    try {
        if (photoUrl) {
            imageUrl = await uploadImageFromUrl(photoUrl);
        } else if (file) {
            imageUrl = await uploadImageFromFile(file);
        } else {
            throw new Error("Debes ingresar una URL o seleccionar un archivo.");
        }

        await sendToApi(imageUrl, accountNumber);
    } catch (error) {
        alert(`Error: ${error.message}`);
    }
});
      
      btnEditProfile.addEventListener('click', function() {
        let inputs = document.querySelectorAll('.readonly-input');
        btnEditProfile.classList.add('hidden');
        inputs.forEach(input => {
          input.readOnly = false;
          input.classList.add('bg');
        });
        saveButton.classList.remove('hidden');
        cancelButton.classList.remove('hidden');
      })

      function hiddenEdit() {
        let inputs = document.querySelectorAll('.readonly-input');
        btnEditProfile.classList.remove('hidden');
        inputs.forEach(input => {
          input.readOnly = true;
          input.classList.remove('bg');
        });
        saveButton.classList.add('hidden');
        cancelButton.classList.add('hidden');
      }

      saveButton.addEventListener('click', function() {
        saveButton.classList.add('hidden');
        cancelButton.classList.add('hidden');
        LoadingButton.classList.remove('hidden');
            const inputs = document.querySelectorAll('input[data-key]');
            const dataKeyValues = Array.from(inputs).reduce((acc, input) => {
              acc[input.dataset.key] = input.value;
              return acc;
            }, {});

            console.log(dataKeyValues);
            
            fetch('/api/put/students/profile.php', {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(dataKeyValues)
            })
            .then(response => {
              if (!response.ok) {
                throw new Error('Error');
              }
              return response.json();
            })
            .then(data => {
              saveButton.classList.remove('hidden');
              LoadingButton.classList.add('hidden');
              console.log('Response:', data);
              hiddenEdit();
            })
            .catch(error => {
              console.error('error:', error);
            });
        });

      cancelButton.addEventListener('click', function () {
        hiddenEdit();
      })


      document.addEventListener('submit', function(event) {
        if (event.target.classList.contains('deletePhotoForm')) {
          event.preventDefault();
          
          const form = event.target;
          const filename = form.querySelector('input[name="filename"]').value;
          const account_number = form.querySelector('input[name="account_number"]').value;
          form.querySelector('button[name="delete"]').disabled=true;
          form.querySelector('button[name="delete"]').innerHTML = `<div class="spinner-border text-light" role="status"></div>`;


          fetch('/api/delete/students/photo.php', {
              method: 'DELETE',
              body: new URLSearchParams({
                  account_number: account_number,
                  filename: filename
              })
          })
          .then(response => {
              if (!response.ok) {
                  throw new Error('Error request: ' + response.status);
              }
              return response.json();
          })
          .then(data => {
              
              window.location.reload()
          })
          .catch(error => {
              console.error('Error:', error);
              alert('error: ' + error.message);
          });
        }
      
    });

 

async function uploadImageFromUrl(url) {
    // Validar si es una URL válida
    if (!/^https?:\/\//i.test(url)) {
        throw new Error("La URL ingresada no es válida.");
    }

    // Descargar la imagen y subirla a ImgBB
    const response = await fetch(url);
    if (!response.ok) {
        throw new Error("No se pudo descargar la imagen desde la URL.");
    }
    const blob = await response.blob();
    return uploadToImgBB(blob);
}

async function uploadImageFromFile(file) {
    // Validar el archivo
    if (!file.type.startsWith("image/")) {
        throw new Error("El archivo seleccionado no es una imagen.");
    }
    return uploadToImgBB(file);
}

async function uploadToImgBB(file) {
    const formData = new FormData();
    formData.append("image", file);

    const apiKey = "cee84319e470684665a483c6b90b9ce8";
    const response = await fetch(`https://api.imgbb.com/1/upload?key=${apiKey}`, {
        method: "POST",
        body: formData,
    });

    if (!response.ok) {
        throw new Error("No se pudo subir la imagen ala servidor CDN.");
    }

    const result = await response.json();
    return result.data.url;
}

async function sendToApi(imageUrl, accountNumber) {
    const response = await fetch("/api/post/students/photo.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ account_number: accountNumber, photo_url: imageUrl }),
    });

    if (!response.ok) {
        throw new Error("No se pudo enviar la URL de la imagen a la API.");
    }
    window.location.reload()
}

    </script>
</body>
</html>
