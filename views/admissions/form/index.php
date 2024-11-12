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
    <meta name="version" content="0.2.0">
    <meta name="date" content="29/10/2014">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Admisión</title>
    <link rel="stylesheet" href="/public/css/theme.css">
    <link rel="icon" type="image/png" href="/public/images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="/public/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>
    <?php include './../../../src/components/navbar.php'; ?>

     

    <!-- Modal CSV Upload -->
    <div class="modal fade" id="csvUploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="staticBackdropLabel">Upload a CSV with the admissions results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="csvUploadForm" method="post" enctype="multipart/form-data">
                        <label for="file">Select a CSV file:</label>
                        <input type="file" name="file" id="file" accept=".csv" required>
                        <button type="submit" name="submit" class="btn btn-primary mt-2">Upload and Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Result Message -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="successModalLabel">Upload Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                    <button id="nextActionBtn" class="btn btn-primary">Validate Applicant Results</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para éxito de validación -->
    <div class="modal fade" id="finalSuccessModal" tabindex="-1" aria-labelledby="finalSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg">
                <div class="modal-header bg">
                    <h5 class="modal-title text" id="finalSuccessModalLabel">Task Completed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>The validation of admission results has been successful.</p>
                    <button id="goToNextTask" class="btn btn-success">E-mail Results</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Formulario principal -->
    <div class="container mt-5">
        <form class="needs-validation bg rounded p-4" novalidate method="POST" action="/api/post/admissions/form.php" enctype="multipart/form-data">
            <div class="form-row d-flex gap-4">
                <div class="col mb-3">
                    <input name="name" type="text" class="form-control" id="validationCustom01" placeholder="Names"  required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="col mb-3">
                    <input name="lastName" type="text" class="form-control" id="validationCustom02" placeholder="Surnames" required>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="form-group">
                    <input name="identity" id="identity" type="text" class="form-control" placeholder="Identity Number" maxlength="15" required>
                </div>
            </div>

            <div class="form-row d-flex gap-4">
                <div class="col-3 mb-3">
                    <input name="phone" id="phone" type="text" class="form-control" placeholder="Phone number" maxlength="9" required>
                    <div class="valid-feedback">Looks good!</div>
                </div>
                <div class="col mb-3">
                    <input name="email" id="email" type="email" class="form-control" placeholder="Email" required>
                    <div class="valid-feedback">Looks good!</div>
                </div>
            </div>

            <div class="row mt-4 mb-2">
                <div class="form-group">
                    <select name="regionalCenter" class="form-control" id="regionalCenter">
                        <option value="">Select regional center</option>
                        <?php while ($center = $regionalCenters->fetch_assoc()): ?>
                            <option value="<?php echo $center['center_id']; ?>"><?php echo $center['center_name']; ?></option>
                        <?php endwhile; ?>
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
                    <select name="secondaryCareer" class="form-control" id="secondaryCareer" disabled>
                        <option value="">Select secondary career</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="form-group row mt-2">
                    <label for="certify" class="col-3">High school certificate.</label>
                    <div class="col">
                        <input name="certify" accept="image/*" type="file" class="form-control-file" id="certify" required>
                        <div class="invalid-feedback">File no upload</div>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Submit form</button>
        </form>
    </div>

    <script src="/public/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/js/formAdmissions.js"></script>
    <script src="/public/js/csvUploadForm.js"></script>
    <script src="/public/js/applicantResultValidation.js"></script>
    <script src="/public/js/emailApplicantResult.js"></script>
</body>
</html>
