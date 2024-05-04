<div class="card">
    <div class="card mb-5 mb-xl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Daftar Kelurahan</span>
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
                        <a href="#" class="menu-link px-3" data-kt-kota-modal-form="add_row_village" data-target="#kt_modal_village">New Kelurahan</a>
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
                <div class="d-flex flex-column mb-5 fv-row">
                    <label class="required fs-6 fw-bold mb-2">Provinsi</label>
                    <select data-control="select2" data-placeholder="Select a Provinsi..." class="form-select form-select-solid" name="select_province_id" id="select_province_id">
                        <option value="">Select Provinsi...</option>
                        <?php
                        $query = "SELECT * FROM provinces WHERE province_status = 1";
                        $result = $koneksi->query($query);
                        while ($provinsi = $result->fetch_assoc()) {
                        ?>
                            <option value="<?= $provinsi['id']; ?>"><?= $provinsi['province_name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_villages_table">
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th>id</th>
                            <th>Provinsi</th>
                            <th>Kelurahan</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Status</th>
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
<script src="villages/villages_script.js"></script>
<?php include 'villages/villages_form.php'; ?>