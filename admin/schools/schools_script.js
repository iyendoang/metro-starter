var KTDatatableSchools = (function () {
  var table_schools;
  var submitButton;
  var cancelButton;
  var validator;
  var form;
  var modal;
  var modalEl;
  var openModalSchoolAdd;
  var openModalSchoolEdit;

  var initTableSchools = function () {
    table_schools = $("#kt_schools_table").DataTable({
      processing: true,
      ajax: {
        url: "schools/schools_action.php?pg=index",
        type: "POST",
      },
      columns: [
        { data: "no" },
        { data: "school_name" },
        { data: "school_npsn" },
        { data: "school_nsm" },
        { data: "jenjang_alias" },
        // { data: "regency_name" },
        { data: "district_name" },
        { data: "school_status" },
        { data: "school_active" },
        {
          data: null,
          orderable: false,
          searchable: false,
          className: "text-end min-w-100px",
          target: -1,
          render: function (data, type, row) {
            return (
              '<button data-id="' +
              row.school_id +
              '" data-kt-provinsi-modal-form="edit_row_school" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">' +
              '<span class="svg-icon svg-icon-3">' +
              '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
              '<path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"></path>' +
              '<path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"></path>' +
              "</svg>" +
              "</span>" +
              '</button> <button data-id="' +
              row.school_id +
              '" data-kt-school-table-filter="delete_row_school" class="btn btn-icon btn-active-light-danger w-30px h-30px me-3">' +
              '<span class="svg-icon svg-icon-3">' +
              '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
              '<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>' +
              '<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>' +
              '<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>' +
              "</svg>" +
              "</span>" +
              "</button>"
            );
          },
        },
      ],
      dom:
      '<"d-flex justify-content-between align-items-center header-actions text-nowrap mx-1 row mt-75"' +
      '<"col-sm-12 col-lg-2 d-flex justify-content-center justify-content-lg-start" l>' +
      '<"col-sm-12 col-lg-10"<"dt-action-buttons d-flex align-items-center justify-content-lg-end justify-content-center flex-md-nowrap flex-wrap"' +
      '<"jenjangSchool mt-50 width-100 me-50"><"districtSchool mt-50 width-100 me-50"><"statusSchool mt-50 width-100 me-50"><"appSchool mt-50 width-100 me-50"><f>>>' +
      '><"text-nowrap" t>' +
      '<"d-flex justify-content-between mx-2 row mb-1"' +
      "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
      "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
      ">",
    initComplete: function () {
      this.api().columns().every(function () {
        var column = this;
        if (column.index() == 4) {
          // Filter untuk kolom jenjang
          var select = $('<select class="form-select form-select-sm"><option value="">Jenjang</option></select>')
            .appendTo('.jenjangSchool')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );
    
              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });
    
          column.data().unique().sort().each(function (d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        }
    
        // if (column.index() == 5) {
        //   // Filter untuk kolom kabupaten
        //   var select = $('<select class="form-select form-select-sm"><option value="">Kota</option></select>')
        //     .appendTo('.regencySchool')
        //     .on('change', function () {
        //       var val = $.fn.dataTable.util.escapeRegex(
        //         $(this).val()
        //       );
    
        //       column
        //         .search(val ? '^' + val + '$' : '', true, false)
        //         .draw();
        //     });
    
        //   column.data().unique().sort().each(function (d, j) {
        //     select.append('<option value="' + d + '">' + d + '</option>')
        //   });
        // }
        if (column.index() == 5) {
          // Filter untuk kolom kabupaten
          var select = $('<select class="form-select form-select-sm"><option value="">Kecamatan</option></select>')
            .appendTo('.districtSchool')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );
    
              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });
    
          column.data().unique().sort().each(function (d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        }
        if (column.index() == 6) {
          // Filter untuk kolom kabupaten
          var select = $('<select class="form-select form-select-sm"><option value="">Status</option></select>')
            .appendTo('.statusSchool')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );
    
              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });
    
          column.data().unique().sort().each(function (d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        }
        if (column.index() == 7) {
          // Filter untuk kolom kabupaten
          var select = $('<select class="form-select form-select-sm"><option value="">App</option></select>')
            .appendTo('.appSchool')
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );
    
              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });
    
          column.data().unique().sort().each(function (d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        }
      });
    }
    
    });
    table_schools.on("draw", function () {
      handleEditForm();
      KTMenu.createInstances();
    });
  };

  var handleDeleteRows = () => {
    $("#kt_schools_table").on(
      "click",
      '[data-kt-school-table-filter="delete_row_school"]',
      function () {
        var school_id = $(this).data("id");
        var schoolFullName = $(this).closest("tr").find("td:eq(1)").text();
        Swal.fire({
          text: "Are you sure you want to delete " + schoolFullName + "?",
          icon: "warning",
          showCancelButton: true,
          buttonsStyling: false,
          confirmButtonText: "Yes, delete!",
          cancelButtonText: "No, cancel",
          customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary",
          },
        }).then(function (result) {
          if (result.isConfirmed) {
            $.ajax({
              url: "schools/schools_action.php?pg=delete",
              type: "POST",
              data: { school_id: school_id },
              success: function (response) {
                if (response.status === 200) {
                  Swal.fire({
                    text: "You have deleted " + schoolFullName + "!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  });
                  table_schools.ajax.reload();
                } else {
                  Swal.fire({
                    text: "Failed to delete " + schoolFullName + ".",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn fw-bold btn-primary",
                    },
                  });
                }
              },
              error: function () {
                Swal.fire({
                  text: "Failed to delete " + schoolFullName + ".",
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                  },
                });
              },
            });
          }
        });
      }
    );
  };

  var handleForm = function () {
    openModalSchoolAdd.forEach((button) => {
      button.addEventListener("click", () => {
        $("#school_action").val("store");
        $("#school_id").val("");
        $("#school_npsn").val("");
        $("#school_nsm").val("");
        $("#school_name").val("");
        $("#jenjang_id").val("").trigger("change");
        $("#school_status").val("").trigger("change");
        $("#school_phone").val("");
        $("#province_id").val("").trigger("change");
        $("#regency_id").val("").trigger("change");
        $("#district_id").val("").trigger("change");
        $("#village_id").val("").trigger("change");
        $("#school_address").val("");
        $("#school_status").prop("checked", false);
        modal.show();
      });
    });
    validator = FormValidation.formValidation(form, {
      fields: {
        school_name: {
          validators: {
            notEmpty: {
              message: "Nama provinsi is required",
            },
          },
        },
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap: new FormValidation.plugins.Bootstrap5({
          rowSelector: ".fv-row",
          eleInvalidClass: "",
          eleValidClass: "",
        }),
      },
    });
    submitButton.addEventListener("click", function (e) {
      e.preventDefault();
      if (validator) {
        validator.validate().then(function (status) {
          if (status == "Valid") {
            var url =
              $("#school_action").val() === "update"
                ? "schools/schools_action.php?pg=update"
                : "schools/schools_action.php?pg=store";
            var formData = $(form).serialize();
            $.ajax({
              url: url,
              type: "POST",
              data: formData,
              success: function (response) {
                submitButton.removeAttribute("data-kt-indicator");
                submitButton.disabled = false;
                Swal.fire({
                  text: response.message,
                  icon: response.label,
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                }).then(function (result) {
                  if (result.isConfirmed) {
                    modal.hide();
                  }
                });
                table_schools.ajax.reload();
              },
              error: function (xhr, status, error) {
                Swal.fire({
                  text: "Error occurred while submitting the form.",
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
                submitButton.removeAttribute("data-kt-indicator");
                submitButton.disabled = false;
              },
            });
            submitButton.setAttribute("data-kt-indicator", "on");
            submitButton.disabled = true;
          } else {
            Swal.fire({
              text: "Please fill all required fields and try again.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
          }
        });
      }
    });
    cancelButton.addEventListener("click", function (e) {
      e.preventDefault();
      Swal.fire({
        text: "Are you sure you would like to cancel?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, cancel it!",
        cancelButtonText: "No, return",
        customClass: {
          confirmButton: "btn btn-primary",
          cancelButton: "btn btn-active-light",
        },
      }).then(function (result) {
        if (result.value) {
          form.reset(); // Reset form
          modal.hide(); // Hide modal
        } else if (result.dismiss === "cancel") {
          Swal.fire({
            text: "Your form has not been cancelled!.",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
        }
      });
    });
  };

  var handleEditForm = () => {
    openModalSchoolEdit = document.querySelectorAll(
      '[data-kt-provinsi-modal-form="edit_row_school"]'
    );
    openModalSchoolEdit.forEach((button) => {
      button.addEventListener("click", function () {
        const school_id = this.getAttribute("data-id");
        $.ajax({
          url: "schools/schools_action.php?pg=show",
          type: "POST",
          data: { school_id: school_id },
          success: function (response) {
            $("#school_action").val("update");
            $("#school_id").val(response.school_id);
            $("#school_npsn").val(response.school_npsn);
            $("#school_nsm").val(response.school_nsm);
            $("#school_name").val(response.school_name);
            $("#jenjang_id").append('<option value="' + response.jenjang_id + '">' + response.jenjang_name + '</option>').val(response.jenjang_id).trigger("change");
            $("#school_status").val(response.school_status).trigger("change");
            $("#school_phone").val(response.school_phone);

            $("#province_id").append('<option value="' + response.province_id + '">' + response.province_name + '</option>');
            $("#province_id").val(response.province_id);
            
            $("#regency_id").append('<option value="' + response.regency_id + '">' + response.regency_name + '</option>');
            $("#regency_id").val(response.regency_id);
            
            $("#district_id").append('<option value="' + response.district_id + '">' + response.district_name + '</option>');
            $("#district_id").val(response.district_id);
            
            $("#village_id").append('<option value="' + response.village_id + '">' + response.village_name + '</option>');
            $("#village_id").val(response.village_id);
            
            $("#school_address").val(response.school_address);
            $("#school_active").prop("checked", response.school_active === 1);
            modal.show();
          },
          error: function () {
            Swal.fire({
              text: "Failed to fetch school data.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
          },
        });
      });
    });
  };
  return {
    init: function () {
      initTableSchools();
      handleDeleteRows();
      modalEl = document.querySelector("#kt_modal_school");
      if (!modalEl) {
        return;
      }
      modal = new bootstrap.Modal(modalEl);
      form = document.querySelector("#kt_modal_school_form");
      submitButton = document.getElementById("kt_modal_school_submit");
      cancelButton = document.getElementById("kt_modal_school_cancel");
      openModalSchoolAdd = document.querySelectorAll(
        '[data-kt-provinsi-modal-form="add_row_school"]'
      );
      handleForm();
      handleEditForm();
    },
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  KTDatatableSchools.init();
});
