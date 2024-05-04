var KTDatatableProvinces = (function () {
  var table_regencies;
  var submitButton;
  var cancelButton;
  var validator;
  var form;
  var modal;
  var modalEl;
  var openModalProvinceAdd;
  var openModalProvinceEdit;
  var initForm = function () {
    $(form.querySelector('[name="province_id"]')).on("change", function () {
      validator.revalidateField("province_id");
    });
  };
  var initTableProvinces = function () {
    table_regencies = $("#kt_regencies_table").DataTable({
      processing: true,
      ajax: {
        url: "regencies/regencies_action.php?pg=index",
        type: "POST",
      },
      columns: [
        { data: "id" },
        { data: "province_name" },
        { data: "regency_name" },
        { data: "regency_status" },
        {
          data: null,
          orderable: false,
          searchable: false,
          className: "text-end min-w-100px",
          target: -1,
          render: function (data, type, row) {
            return (
              '<button data-id="' +
              row.id +
              '" data-kt-kota-modal-form="edit_row_regency" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">' +
              '<span class="svg-icon svg-icon-3">' +
              '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
              '<path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"></path>' +
              '<path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"></path>' +
              "</svg>" +
              "</span>" +
              '</button> <button data-id="' +
              row.id +
              '" data-kt-regency-table-filter="delete_row_regency" class="btn btn-icon btn-active-light-danger w-30px h-30px me-3">' +
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
        "<'row mb-2'" +
        "<'col-sm-6 d-flex align-items-center justify-conten-start dt-toolbar'l>" +
        "<'col-sm-6 d-flex align-items-center justify-content-end dt-toolbar'f>" +
        ">" +
        "<'table-responsive'tr>" +
        "<'row'" +
        "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
        "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
        ">",
    });
    table_regencies.on("draw", function () {
      handleEditForm();
      KTMenu.createInstances();
    });
  };

  var handleDeleteRows = () => {
    $("#kt_regencies_table").on(
      "click",
      '[data-kt-regency-table-filter="delete_row_regency"]',
      function () {
        var id = $(this).data("id");
        var regencyFullName = $(this).closest("tr").find("td:eq(1)").text();
        Swal.fire({
          text: "Are you sure you want to delete " + regencyFullName + "?",
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
              url: "regencies/regencies_action.php?pg=delete",
              type: "POST",
              data: { id: id },
              success: function (response) {
                if (response.status === 200) {
                  Swal.fire({
                    text: "You have deleted " + regencyFullName + "!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  });
                  table_regencies.ajax.reload();
                } else {
                  Swal.fire({
                    text: "Failed to delete " + regencyFullName + ".",
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
                  text: "Failed to delete " + regencyFullName + ".",
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
    openModalProvinceAdd.forEach((button) => {
      button.addEventListener("click", () => {
        $("#regency_action").val("store");
        $("#regency_id").val("");
        $("#province_province_id").val("").trigger("change");
        $("#regency_regency_name").val("");
        $("#regency_status").prop("checked", false);
        modal.show();
      });
    });
    validator = FormValidation.formValidation(form, {
      fields: {
        province_id: {
          validators: {
            notEmpty: {
              message: "ID provinsi is required",
            },
          },
        },
        id: {
          validators: {
              notEmpty: {
                  message: "ID kota is required",
              },
              stringLength: {
                  min: 1,
                  max: 4,
                  message: "The ID kota must be between 1 and 4 digits long",
              },
              regexp: {
                  regexp: /^[0-9]{1,4}$/,
                  message: "ID kota must be a number",
              },
          },
      },
      
        regency_name: {
          validators: {
            notEmpty: {
              message: "Nama kota is required",
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
              $("#regency_action").val() === "update"
                ? "regencies/regencies_action.php?pg=update"
                : "regencies/regencies_action.php?pg=store";
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
                table_regencies.ajax.reload();
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
    openModalProvinceEdit = document.querySelectorAll(
      '[data-kt-kota-modal-form="edit_row_regency"]'
    );
    openModalProvinceEdit.forEach((button) => {
      button.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        $.ajax({
          url: "regencies/regencies_action.php?pg=show",
          type: "POST",
          data: { id: id },
          success: function (response) {
            $("#regency_action").val("update");
            $("#province_province_id")
              .val(response.province_id)
              .trigger("change");
            $("#id_id_old").val(response.id);
            $("#regency_id").val(response.id);
            $("#regency_regency_name").val(response.regency_name);
            if (response.regency_status === 1) {
              $("#regency_status").prop("checked", true);
            } else {
              $("#regency_status").prop("checked", false);
            }
            modal.show();
          },
          error: function () {
            Swal.fire({
              text: "Failed to fetch regency data.",
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
      initTableProvinces();
      handleDeleteRows();
      modalEl = document.querySelector("#kt_modal_regency");
      if (!modalEl) {
        return;
      }
      modal = new bootstrap.Modal(modalEl);
      form = document.querySelector("#kt_modal_regency_form");
      submitButton = document.getElementById("kt_modal_regency_submit");
      cancelButton = document.getElementById("kt_modal_regency_cancel");
      openModalProvinceAdd = document.querySelectorAll(
        '[data-kt-kota-modal-form="add_row_regency"]'
      );
      handleForm();
      handleEditForm();
      initForm();
    },
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  KTDatatableProvinces.init();
});
