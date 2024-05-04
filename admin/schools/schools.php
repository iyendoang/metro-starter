<div class="card">
    <div class="card mb-5 mb-xl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Daftar Sekolah</span>
                <span class="text-muted mt-1 fw-bold fs-7">Over 500 orders</span>
            </h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="Aksi Baru">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="5" y="5" width="5" height="5" rx="1" fill="currentColor" />
                                <rect x="14" y="5" width="5" height="5" rx="1" fill="currentColor" opacity="0.3" />
                                <rect x="5" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3" />
                                <rect x="14" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </button>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content fs-6 text-dark fw-bolder px-3 py-4">Actions</div>
                    </div>
                    <div class="separator mb-3 opacity-75"></div>
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-provinsi-modal-form="add_row_school" data-target="#kt_modal_school">New Sekolah</a>
                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_school_import">Import Sekolah</a>
                        <!-- <a href="#" id="downloadButton" class="menu-link px-3">Download Excel</a> -->
                    </div>
                    <div class="separator mt-3 opacity-75"></div>
                    <div class="menu-item px-3">
                        <div class="menu-content px-3 py-3">
                            <a class="btn btn-primary btn-sm px-4" href="#">Generate Action</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 table-responsive" id="kt_schools_table">
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th>id</th>
                            <th>Nama Sekolah</th>
                            <th>NPSN</th>
                            <th>NSS/NSM</th>
                            <th>Jenjang</th>
                            <!-- <th>Kota</th> -->
                            <th>Kecamatan</th>
                            <th>Status</th>
                            <th>App</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-bold">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
s
<script src="schools/schools_script.js"></script>
<?php include 'schools/schools_form.php'; ?>
<?php include 'schools/school_import_modal.php'; ?>
<script>
    $(document).ready(function() {
        $('#downloadButton').click(function() {
            $.ajax({
                url: 'schools/schools_export.php?pg=export',
                method: 'GET',
                success: function(response) {
                    // Handle success response
                    console.log(response);
                    if (response.status === 200) {
                        // Redirect to the download link
                        window.location.href = response.downloadLink;
                    } else {
                        // Display error message
                        alert('Failed to export data: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('Failed to export data: ' + error);
                }
            });
        });
    });
</script>