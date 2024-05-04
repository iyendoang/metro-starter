        <div class="modal fade" id="kt_modal_village" tabindex="-1" aria-hidden="true" data-bs-focus="false">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content rounded">
                    <form class="form" action="#" id="kt_modal_village_form">
                        <input type="hidden" name="action" id="village_action" />
                        <input type="hidden" name="id_old" id="id_id_old" />
                        <div class="modal-header" id="kt_modal_village_header">
                            <h2>Add New Address</h2>
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                        <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                                        <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                                    </svg>
                                </span>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-bold">
                                        <h4 class="text-gray-900 fw-bolder">Warning</h4>
                                        <div class="fs-6 text-gray-700">Harap isi dengan data benar
                                            <a href="#">Berkaitan dengan relasi data</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="required fs-6 fw-bold mb-2">Provinsi</label>
                                <select data-control="select2" data-placeholder="Select a Provinsi..." class="form-select form-select-solid" name="province_id" id="province_province_id">
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
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="required fs-6 fw-bold mb-2">Kota</label>
                                <select data-control="select2" data-placeholder="Select a Kota..." class="form-select form-select-solid" name="regency_id" id="regency_regency_id">
                                    <option value="">Select Kota...</option>
                                </select>
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="required fs-6 fw-bold mb-2">Kecamatan</label>
                                <select data-control="select2" data-placeholder="Select a Kecamatan..." class="form-select form-select-solid" name="district_id" id="district_district_id">
                                    <option value="">Select Kecamatan...</option>
                                </select>
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="required fs-5 fw-bold mb-2">ID Kelurahan</label>
                                <input class="form-control form-control-solid" placeholder="ID Kelurahan" name="id" id="village_id" />
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="required fs-5 fw-bold mb-2">Nama Kelurahan</label>
                                <input class="form-control form-control-solid" placeholder="Nama Kelurahan" name="village_name" id="village_village_name" />
                            </div>
                            <div class="fv-row mb-5">
                                <div class="d-flex flex-stack">
                                    <div class="me-5">
                                        <label class="fs-5 fw-bold">Status Kelurahan?</label>
                                        <div class="fs-7 fw-bold text-muted">Status aktif akan tampil pada opsi provinsi</div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" name="village_status" id="village_village_status" type="checkbox" value="1" checked="checked" />
                                        <span class="form-check-label fw-bold text-muted">Yes</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="reset" id="kt_modal_village_cancel" class="btn btn-light me-3">Discard</button>
                            <button type="submit" id="kt_modal_village_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="villages/wilayah_scripts.js"></script>