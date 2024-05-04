        <div class="modal fade" id="kt_modal_school" tabindex="-1" aria-hidden="true" data-bs-focus="false">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded">
                    <form class="form" action="#" id="kt_modal_school_form">
                        <input type="hidden" name="action" id="school_action" />
                        <input type="hidden" name="school_id" id="school_id" />
                        <div class="modal-header" id="kt_modal_school_header">
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
                        <div class="modal-body scroll-y px-10 px-lg-10 pt-10 pb-10">
                            <div class="row mb-5">
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">Nama Sekolah</label>
                                    <input class="form-control form-control-solid" placeholder="Nama Sekolah" name="school_name" id="school_name" />
                                </div>
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">NPSN</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="NPSN Sekolah" name="school_npsn" id="school_npsn" />
                                </div>
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">NSM/NSS</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="NSM/NSS" name="school_nsm" id="school_nsm" />
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">Jenjang</label>
                                    <select data-control="select2" data-placeholder="Select a Jenjang..." class="form-select form-select-solid" name="jenjang_id" id="jenjang_id">
                                        <option value="">Select Jenjang...</option>
                                        <?php
                                        $query = "SELECT * FROM jenjangs WHERE jenjang_status = 1 ORDER BY jenjang_sort ASC";
                                        $result = $koneksi->query($query);
                                        while ($jenjang = $result->fetch_assoc()) {
                                        ?>
                                            <option value="<?= $jenjang['jenjang_id']; ?>"><?= $jenjang['jenjang_name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">Status</label>
                                    <select data-control="select2" data-placeholder="Select a Status..." class="form-select form-select-solid" name="school_status" id="school_status">
                                        <option value="">Select Status...</option>
                                        <option value="NEGERI">NEGERI</option>
                                        <option value="SWASTA">SWASTA</option>
                                    </select>
                                </div>
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">No Handphone</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="No Handphone" name="school_phone" id="school_phone" />
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-6 fw-bold mb-2">Provinsi</label>
                                    <select data-control="select2" data-placeholder="Select a Provinsi..." class="form-select form-select-solid" name="province_id" id="province_id">
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
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">Kota</label>
                                    <select data-control="select2" data-placeholder="Select a Kota..." class="form-select form-select-solid" name="regency_id" id="regency_id">
                                        <option value="">Select Kota...</option>
                                    </select>
                                </div>
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">Kecamatan</label>
                                    <select data-control="select2" data-placeholder="Select a Kecamatan..." class="form-select form-select-solid" name="district_id" id="district_id">
                                        <option value="">Select Kecamatan...</option>
                                    </select>
                                    </select>
                                </div>
                                <div class="col-md-3 fv-row">
                                    <label class="required fs-5 fw-bold mb-2">Kelurahan</label>
                                    <select data-control="select2" data-placeholder="Select a Kelurahan..." class="form-select form-select-solid" name="village_id" id="village_id">
                                        <option value="">Select Kelurahan...</option>
                                    </select>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex flex-column mb-5 fv-row">
                                <label class="required fs-5 fw-bold mb-2">Alamat</label>
                                <textarea class="form-control form-control-solid" rows="3" name="school_address" id="school_address" placeholder="Alamat Sekolah"></textarea>
                            </div>
                            <div class="fv-row mb-5">
                                <div class="d-flex flex-stack">
                                    <div class="me-5">
                                        <label class="fs-5 fw-bold">Status Sekolah?</label>
                                        <div class="fs-7 fw-bold text-muted">Status aktif akan tampil pada opsi sekolah</div>
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" name="school_active" id="school_active" type="checkbox" value="1" checked="checked" />
                                        <span class="form-check-label fw-bold text-muted">Yes</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="reset" id="kt_modal_school_cancel" class="btn btn-light me-3">Discard</button>
                            <button type="submit" id="kt_modal_school_submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="schools/wilayah_scripts.js"></script>