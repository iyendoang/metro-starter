<?php
session_start();
if (!isset($_SESSION['id'])) {
?>
    <!DOCTYPE html>
    <html lang="en">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

    <head>
        <title>Sign-up - Portal Sidoel</title>
        <meta charset="utf-8" />
        <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
        <meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
        <meta property="og:url" content="https://keenthemes.com/metronic" />
        <meta property="og:site_name" content="Keenthemes | Metronic" />
        <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
        <link rel="shortcut icon" href="assets/images/logos/sidoel.ico" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    </head>

    <body id="kt_body" class="bg-body">
        <div class="d-flex flex-column flex-root">
            <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
                <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                    <a href="." class="mb-12">
                        <img alt="Logo" src="assets/images/logos/sidoel-light.png" class="h-80px" />
                    </a>
                    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form">
                            <div class="mb-10 text-center">
                                <h1 class="text-dark mb-3">Create an Account</h1>
                                <div class="text-gray-400 fw-bold fs-4">Already have an account?
                                    <a href="login.php" class="link-primary fw-bolder">Sign in here</a>
                                </div>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="form-label fw-bolder text-dark fs-6">Full Name</label>
                                <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Full Name" name="fullname" autocomplete="off" />
                            </div>
                            <div class="row fv-row mb-7">
                                <div class="col-xl-6">
                                    <label class="form-label fw-bolder text-dark fs-6">Username</label>
                                    <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Username" name="username" autocomplete="off" />
                                </div>
                                <div class="col-xl-6">
                                    <label class="required fs-6 fw-bold mb-2">Gender</label>
                                    <select data-placeholder="Select a Gender..." class="form-select form-select-solid" name="gender" id="gender" autocomplete="off">
                                        <option value="">Select Gender...</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="form-label fw-bolder text-dark fs-6">Phone</label>
                                <input class="form-control form-control-lg form-control-solid" type="text" placeholder="Ho Handphone" name="phone" autocomplete="off" />
                            </div>
                            <div class="row fv-row mb-7">
                                <div class="col-xl-6">
                                    <label class="required fs-6 fw-bold mb-2">Provinsi</label>
                                    <select data-control="select2" data-placeholder="Select a Provinsi..." class="form-select form-select-solid" name="province_id" id="province_id">
                                        <option value="">Select Provinsi...</option>
                                    </select>
                                </div>
                                <div class="col-xl-6">
                                    <label class="required fs-6 fw-bold mb-2">Kota</label>
                                    <select data-control="select2" data-placeholder="Select a Kota..." class="form-select form-select-solid" name="regency_id" id="regency_id">
                                        <option value="">Select Kota...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row fv-row mb-7">
                                <div class="col-xl-6">
                                    <label class="required fs-6 fw-bold mb-2">Kecamatan</label>
                                    <select data-control="select2" data-placeholder="Select a Kecamatan..." class="form-select form-select-solid" name="district_id" id="district_id">
                                        <option value="">Select Kecamatan...</option>
                                    </select>
                                </div>
                                <div class="col-xl-6">
                                    <label class="required fs-6 fw-bold mb-2">Sekolah / Madrasah</label>
                                    <select data-control="select2" data-placeholder="Cari dengan NPSN" class="form-select form-select-solid" name="school_npsn" id="school_npsn">
                                        <option value="">Select Sekolah / Madrasah...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-10 fv-row" data-kt-password-meter="true">
                                <div class="mb-1">
                                    <label class="form-label fw-bolder text-dark fs-6">Password</label>
                                    <div class="position-relative mb-3">
                                        <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="password" autocomplete="off" />
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                            <i class="bi bi-eye-slash fs-2"></i>
                                            <i class="bi bi-eye fs-2 d-none"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>
                                </div>
                                <div class="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
                            </div>
                            <div class="fv-row mb-5">
                                <label class="form-label fw-bolder text-dark fs-6">Confirm Password</label>
                                <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm-password" autocomplete="off" />
                            </div>
                            <div class="fv-row mb-10">
                                <label class="form-check form-check-custom form-check-solid form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="toc" value="1" />
                                    <span class="form-check-label fw-bold text-gray-700 fs-6">I Agree
                                        <a href="#" class="ms-1 link-primary">Terms and conditions</a>.</span>
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="button" id="kt_sign_up_submit" class="btn btn-lg btn-primary">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="d-flex flex-center flex-column-auto p-10">
                    <div class="d-flex align-items-center fw-bold fs-6">
                        <a href="https://keenthemes.com/" class="text-muted text-hover-primary px-2">About</a>
                        <a href="mailto:support@keenthemes.com" class="text-muted text-hover-primary px-2">Contact</a>
                        <a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var hostUrl = "assets/index.html";
        </script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/js/scripts/sign-up.js"></script>
    </body>

    </html>
<?php
} else {
    header("Location: index.php");
}
?>