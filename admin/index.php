<?php
session_start();
require("../config/database.php");
require("../config/functions.php");
if (isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$_SESSION[id]'");
    if ($query) {
        $user = mysqli_fetch_array($query);
?>
        <!DOCTYPE html>
        <html lang="en" data-bs-theme-mode="light">
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

        <head>
            <title>Metronic - the world's #1 selling Bootstrap Admin Theme Ecosystem for HTML, Vue, React, Angular &amp; Laravel by Keenthemes</title>
            <meta charset="utf-8" />
            <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
            <meta name="keywords" content="Aplikasi Pusat Pengelolaan User, Aplikasi Pengelolaan User" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <meta property="og:locale" content="en_US" />
            <meta property="og:type" content="article" />
            <meta property="og:title" content="SIDOEL API &amp; DATA | Aplikasi Pusat Pengelolaan User" />
            <link rel="shortcut icon" href="../assets/images/logos/sidoel.ico" />
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
            <link href="../assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
            <link href="../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
            <link href="../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
            <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
        </head>

        <body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">
            <div class="d-flex flex-column flex-root">
                <div class="page d-flex flex-row flex-column-fluid">
                    <div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
                        <div class="aside-logo flex-column-auto" id="kt_aside_logo">
                            <a href="../">
                                <img alt="Logo" src="../assets/images/logos/sidoel.png" class="h-35px logo" />
                                <img alt="Logo" src="../assets/images/logos/banner-dark.png" class="h-30px logo" />
                            </a>
                            <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
                                <span class="svg-icon svg-icon-1 rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
                                        <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!--begin::Aside menu-->
                        <?php include('layouts/_menu.php'); ?>
                        <!--end::Aside menu-->
                    </div>
                    <!--end::Aside-->
                    <!--begin::Wrapper-->
                    <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                        <!--begin::Header-->
                        <?php include('layouts/_toolbar.php'); ?>
                        <?php include('layouts/_header.php'); ?>
                        <!--end::Header-->
                        <!--begin::Content-->
                        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                            <!--begin::Post-->
                            <div class="post d-flex flex-column-fluid" id="kt_post">
                                <!--begin::Container-->
                                <div id="kt_content_container" class="container-xxl">
                                    <!--begin::Row-->
                                    <?php include('layouts/_content.php'); ?>
                                    <!--end::Row-->
                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Post-->
                        </div>
                        <!--end::Content-->
                        <!--begin::Footer-->
                        <?php include 'layouts/_footer.php'; ?>
                        <!--end::Footer-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Page-->
            </div>
            <!--begin::Scrolltop-->
            <?php include 'layouts/_scrolltop.php'; ?>
            <!--end::Scrolltop-->
            <!--begin::Javascript-->
            <script>
                var hostUrl = "../assets/index.html";
            </script>
            <!--begin::Global Javascript Bundle(used by all pages)-->
            <script src="../assets/plugins/global/plugins.bundle.js"></script>
            <script src="../assets/js/scripts.bundle.js"></script>
            <!--end::Global Javascript Bundle-->
            <!--begin::Page Vendors Javascript(used by this page)-->
            <script src="../assets/plugins/custom/datatables/datatables.bundle.js"></script>
            <script src="../assets/js/widgets.bundle.js"></script>
            <!--end::Page Vendors Javascript-->
            <!--end::Javascript-->
        </body>

        </html>
<?php
    } else {
        header("Location: ../login.php");
    }
} else {
    header("Location: ../login.php");
}
?>