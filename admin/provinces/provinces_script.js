var KTDatatableProvinces = (function () {
  var table_provinces;
  var submitButton;
  var cancelButton;
  var validator;
  var form;
  var modal;
  var modalEl;
  var openModalProvinceAdd;
  var openModalProvinceEdit;

  var initTableProvinces = function () {
    table_provinces = $("#kt_provinces_table").DataTable({
      processing: true,
      ajax: {
        url: "provinces/provinces_action.php?pg=index",
        type: "POST",
      },
      columns: [
        { data: "id" },
        { data: "province_name" },
        { data: "province_status" },
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
              '" data-kt-provinsi-modal-form="edit_row_province" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">' +
              '<span class="svg-icon svg-icon-3">' +
              '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">' +
              '<path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor"></path>' +
              '<path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor"></path>' +
              "</svg>" +
              "</span>" +
              '</button> <button data-id="' +
              row.id +
              '" data-kt-province-table-filter="delete_row_province" class="btn btn-icon btn-active-light-danger w-30px h-30px me-3">' +
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
    table_provinces.on("draw", function () {
      handleEditForm();
      KTMenu.createInstances();
    });
  };

  var handleDeleteRows = () => {
    $("#kt_provinces_table").on(
      "click",
      '[data-kt-province-table-filter="delete_row_province"]',
      function () {
        var id = $(this).data("id");
        var provinceFullName = $(this).closest("tr").find("td:eq(1)").text();
        Swal.fire({
          text: "Are you sure you want to delete " + provinceFullName + "?",
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
              url: "provinces/provinces_action.php?pg=delete",
              type: "POST",
              data: { id: id },
              success: function (response) {
                if (response.status === 200) {
                  Swal.fire({
                    text: "You have deleted " + provinceFullName + "!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  });
                  table_provinces.ajax.reload();
                } else {
                  Swal.fire({
                    text: "Failed to delete " + provinceFullName + ".",
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
                  text: "Failed to delete " + provinceFullName + ".",
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
        $("#province_action").val("store");
        $("#province_id").val("");
        $("#province_province_name").val("");
        $("#province_status").prop("checked", false);
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
        province_name: {
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
              $("#province_action").val() === "update"
                ? "provinces/provinces_action.php?pg=update"
                : "provinces/provinces_action.php?pg=store";
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
                table_provinces.ajax.reload();
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
      '[data-kt-provinsi-modal-form="edit_row_province"]'
    );
    openModalProvinceEdit.forEach((button) => {
      button.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        $.ajax({
          url: "provinces/provinces_action.php?pg=show",
          type: "POST",
          data: { id: id },
          success: function (response) {
            $("#province_action").val("update");
            $("#province_id").val(response.id);
            $("#province_province_name").val(response.province_name);
            if (response.province_status === 1) {
              $("#province_status").prop("checked", true);
            } else {
              $("#province_status").prop("checked", false);
            }
            modal.show();
          },
          error: function () {
            Swal.fire({
              text: "Failed to fetch province data.",
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
      modalEl = document.querySelector("#kt_modal_province");
      if (!modalEl) {
        return;
      }
      modal = new bootstrap.Modal(modalEl);
      form = document.querySelector("#kt_modal_province_form");
      submitButton = document.getElementById("kt_modal_province_submit");
      cancelButton = document.getElementById("kt_modal_province_cancel");
      openModalProvinceAdd = document.querySelectorAll(
        '[data-kt-provinsi-modal-form="add_row_province"]'
      );
      handleForm();
      handleEditForm();
    },
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  KTDatatableProvinces.init();
});
