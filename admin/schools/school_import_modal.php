<!--begin::Modal - Create Api Key-->
<div class="modal fade" id="kt_modal_school_import" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_school_import_header">
                <!--begin::Modal title-->
                <h2>Create IMPORT Key</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <form id="kt_modal_school_import_form" class="form" action="#">
                <div class="modal-body py-10 px-lg-17">
                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-10 p-6">
                        <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                                <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                            </svg>
                        </span>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-bold">
                                <h4 class="text-gray-900 fw-bolder">Please Note!</h4>
                                <div class="fs-6 text-gray-700">Import dengan excel silahkan
                                    <a href="../temp/importschools.xlsx" class="fw-bolder">Download Template</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5 fv-row">
                        <label class="required fs-5 fw-bold mb-2">IMPORT Name</label>
                        <input type="file" class="form-control form-control-solid" placeholder="Your IMPORT Name" name="shcool_import_file" />
                    </div>
                    <div class="modal-footer flex-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="kt_modal_school_import_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#kt_modal_school_import_form').submit(function(e) {
            e.preventDefault(); // Prevent form submission

            var formData = new FormData($(this)[0]); // Create FormData object from form

            // Disable submit button and show loading spinner
            $('#kt_modal_school_import_submit').attr('disabled', true);
            $('#kt_modal_school_import_submit .indicator-label').hide();
            $('#kt_modal_school_import_submit .indicator-progress').show();

            // Send AJAX request
            $.ajax({
                url: 'schools/schools_import.php?pg=import', // Your PHP script to handle the file upload
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from automatically processing the data
                contentType: false, // Prevent jQuery from automatically setting the Content-Type
                success: function(response) {
                    console.log(response);
                    // Enable submit button and hide loading spinner
                    $('#kt_modal_school_import_submit').attr('disabled', false);
                    $('#kt_modal_school_import_submit .indicator-label').show();
                    $('#kt_modal_school_import_submit .indicator-progress').hide();

                    // Parse the response JSON
                    var data = JSON.parse(response);

                    // Check if status is 200 (success)
                    if (data.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            footer: 'Inserted: ' + data.inserted + ', Updated: ' + data.updated + ', Failed: ' + data.failed
                        });
                        setTimeout(function() {
                            location.reload(); // reload page
                        }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#kt_modal_school_import_submit').attr('disabled', false);
                    $('#kt_modal_school_import_submit .indicator-label').show();
                    $('#kt_modal_school_import_submit .indicator-progress').hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to import data. Please try again.'
                    });
                }

            });
        });
    });
</script>