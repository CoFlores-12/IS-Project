<?php
include './../../../src/modules/database.php';

$db = (new Database())->getConnection();
$regionalCenters = $db->execute_query("SELECT * FROM Regional_center");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="cofloresf@unah.hn">
    <meta name="version" content="0.1.0">
    <meta name="date" content="29/10/2014">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantilla</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <?php include './../../../src/components/navbar.php'; ?>
    <div class="buttons">
        <button class="btn bg-custom-primary text-white"  data-bs-toggle="modal" data-bs-target="#csvUploadModal">Upload Admission Results</button>
    </div>
    <!-- Modal CSV Upload
    <div class="modal fade" id="csvUploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg">
            <div class="modal-header bg">
                <h5 class="modal-title text" id="staticBackdropLabel">Upload a CSV with the admissions results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/api/post/admin/uploadCSV.php" method="post" enctype="multipart/form-data">
                    <label for="file">Select a CSV file:</label>
                    <input type="file" name="file" id="file" accept=".csv">
                    <button type="submit" name="submit">Upload and Import</button>
                </form>
            </div>
        </div>
    </div>
    Modal CSV Upload -->
    <div class="modal fade flex justify-center items-center" id="loadingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  flex justify-center items-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="main min-h-full flex bg-aux justify-center items-center p-4">
        
        <form class="needs-validation bg rounded p-4" novalidate method="POST" action="/api/post/admissions/form.php"  enctype="multipart/form-data">
            <div class="form-row flex gap-4">
                <div class="col mb-3">
                    <input name="name" type="text" class="form-control" id="validationCustom01" placeholder="First name"  required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <input name="lastName" type="text" class="form-control" id="validationCustom02" placeholder="Last name" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="form-group">
                    <input name="identity" type="text" class="form-control" id="identity" placeholder="Identity Number" maxlength="15" required>
                </div>
            </div>
            <div class="form-row flex gap-4">
                <div class="col-3 mb-3">
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="Phone number" maxlength="9" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <input name="email" type="email" class="form-control" id="email" placeholder="Email" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
            <div class="row mt-4 mb-2">
                <div class="form-group">
                    <select name="regionalCenter" class="form-control custom-select" id="regionalCenter">
                        <option value="">Select regional center</option>
                        <?php
                            while ($center = $regionalCenters->fetch_assoc()) {
                                echo '<option value="'.$center['center_id'].'">'.$center['center_name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group">
                    <select name="mainCareer" class="form-control" id="mainCareer" disabled>
                        <option value="">Select main career</option>
                        
                    </select>
                </div>
                <div class="form-group mt-2">
                    <select name="secondaryCareer" class="form-control  form-control-sm" id="secondaryCareer" disabled>

                        <option value="">Select secondary career</option>
                       
                    </select>
                </div>
            </div>
            <div class="row mb-4">
                <div class="form-group row mt-2">
                    <label for="certify" class="col-3">High school certificate.</label>
                    <div class="col">
                        <input name="certify" accept="image/*" type="file" class="form-control-file" id="certify" required>
                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                    </div>
                </div>
            </div>
           
            <button class="btn btn-primary" type="submit">Submit form</button>
        </form>

    </div>
    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/formAdmissions.js"></script>
</body>
</html>