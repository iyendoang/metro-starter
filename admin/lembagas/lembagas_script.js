"use strict";
var KTDatatableLembagasSide = (function () {
  var dt;
  var filterRole;
  var submitButton;
  var cancelButton;
  var validator;
  var form;
  var modal;
  var modalEl;
  var initDatatable = function () {
    dt = $("#kt_lembaga_table").DataTable({
      processing: true,
      order: [[1, "asc"]],
      stateSave: true,
      select: {
        style: "multi",
        selector: 'td:first-child input[type="checkbox"]',
        className: "row-selected",
      },
      lengthMenu: [
        [10, 25, 30, 31, 32, 33, 34, 35, 100, -1],
        [10, 25, 30, 31, 32, 33, 34, 35, 100, "All"],
      ],
      pageLength: 10,
      ajax: {
        url: "lembagas/lembagas_action.php?pg=index",
      },
      columns: [
        { data: "lembaga_npsn" },
        { data: "lembaga_name" },
        { data: "lembaga_nsm" },
        { data: "lembaga_nsm" },
        { data: "lembaga_nsm" },
        { data: "lembaga_nsm" },
        { data: null },
      ],
      columnDefs: [
        {
          targets: 0,
          orderable: false,
          render: function (data, type, row) {
            return `
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="${row["lembaga_npsn"]}" data-id="${row["lembaga_npsn"]}"/>
                    </div>`;
          },
        },
        {
          targets: -1,
          data: null,
          orderable: false,
          className: "text-end",
          render: function (data, type, row) {
            return `
              <a href="#" class="btn btn-secondary btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                Actions
                <span class="svg-icon fs-5 m-0">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <polygon points="0 0 24 0 24 24 0 24"></polygon>
                      <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                    </g>
                  </svg>
                </span>
              </a>
              <!--begin::Menu-->
              <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                  <a href="#" class="menu-link px-3" data-id="${data["lembaga_npsn"]}" data-kt-lembaga-table-filter="edit_row_lembaga">
                    Edit
                  </a>
                </div>
                <div class="menu-item px-3">
                  <a href="#" data-id="${data["lembaga_npsn"]}" class="menu-link px-3" data-kt-lembaga-table-filter="delete_row_lembaga">
                    Delete
                  </a>
                </div>
              </div>
            `;
          },
        },
      ],
      createdRow: function (row, data, dataIndex) {
        $(row).find("td:eq(4)").attr("data-filter", data.RoleType);
      },
    });
    dt.on("draw", function () {
      toggleToolbars();
      bulkActionSelected();
      handleDeleteRows();
      handleEditForm();
      KTMenu.createInstances();
    });
  };

  var handleDeleteRows = () => {
    const deleteButtons = document.querySelectorAll(
      '[data-kt-lembaga-table-filter="delete_row_lembaga"]'
    );
    deleteButtons.forEach((d) => {
      d.addEventListener("click", function (e) {
        e.preventDefault();
        const parent = e.target.closest("tr");
        const id = d.dataset.id;
        const lembagaFullName = parent.querySelectorAll("td")[1].innerText;
        Swal.fire({
          text: "Are you sure you want to delete " + lembagaFullName + "?",
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
          if (result.value) {
            $.ajax({
              url: "lembagas/lembagas_action.php?pg=delete",
              type: "POST",
              data: { id: id },
              success: function (response) {
                if (response.status === 200) {
                  Swal.fire({
                    text: "You have deleted " + lembagaFullName + "!.",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  });
                  dt.draw().ajax.reload();
                } else {
                  Swal.fire({
                    text: "Failed to delete " + lembagaFullName + ".",
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
                  text: "Failed to delete " + lembagaFullName + ".",
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                  },
                });
              },
            });
          } else if (result.dismiss === "cancel") {
            Swal.fire({
              text: lembagaFullName + " was not deleted.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn fw-bold btn-primary",
              },
            });
          }
        });
      });
    });
  };
  var handleSearchDatatable = function () {
    const filterSearch = document.querySelector(
      '[data-kt-lembaga-table-filter="search"]'
    );
    filterSearch.addEventListener("keyup", function (e) {
      dt.search(e.target.value).draw();
    });
  };
  var handleFilterDatatable = () => {
    filterRole = document.querySelectorAll(
      '[data-kt-lembaga-table-filter="role_type"]'
    );
    const filterButton = document.querySelector(
      '[data-kt-lembaga-table-filter="filter"]'
    );

    if (filterButton) {
      filterButton.addEventListener("click", function () {
        let roleValue = "";
        if (filterRole.length > 0) {
          roleValue = filterRole[0].value;
        }
        dt.search(roleValue).draw();
      });
    }
  };
  var handleResetForm = () => {
    const resetButton = document.querySelector(
      '[data-kt-lembaga-table-filter="reset"]'
    );
    if (resetButton) {
      resetButton.addEventListener("click", function () {
        dt.search("").draw();
      });
    }
  };
  var bulkActionSelected = function () {
    const container = document.querySelector("#kt_lembaga_table");
    const checkboxes = container.querySelectorAll('[type="checkbox"]');
    const deleteSelected = document.querySelector(
      '[data-kt-lembaga-table-select="delete_selected"]'
    );
    checkboxes.forEach((c) => {
      c.addEventListener("click", function () {
        setTimeout(function () {
          toggleToolbars();
        }, 50);
      });
    });
    if (deleteSelected) {
      deleteSelected.addEventListener("click", function () {
        const selectedIds = [];
        checkboxes.forEach((c) => {
          if (c.checked) {
            selectedIds.push(c.getAttribute("data-id"));
          }
        });
        if (selectedIds.length === 0) {
          Swal.fire({
            text: "Please select at least one lembaga to delete",
            icon: "info",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn fw-bold btn-primary",
            },
          });
          return;
        }
        Swal.fire({
          text: "Are you sure you want to delete selected lembagas?",
          icon: "warning",
          showCancelButton: true,
          buttonsStyling: false,
          showLoaderOnConfirm: true,
          confirmButtonText: "Yes, delete!",
          cancelButtonText: "No, cancel",
          customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary",
          },
        }).then(function (result) {
          if (result.value) {
            $.ajax({
              url: "lembagas/lembagas_action.php?pg=bulk_delete",
              type: "POST",
              data: { ids: selectedIds },
              success: function (response) {
                if (response.status === 200) {
                  Swal.fire({
                    text: "You have deleted all selected lembagas!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn fw-bold btn-primary",
                    },
                  });
                  dt.draw().ajax.reload();
                } else {
                  Swal.fire({
                    text: "Failed to delete selected lembagas.",
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
                  text: "Failed to delete selected lembagas.",
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
      });
    }
  };
  var toggleToolbars = function () {
    const container = document.querySelector("#kt_lembaga_table");
    const toolbarBase = document.querySelector(
      '[data-kt-lembaga-table-toolbar="base"]'
    );
    const toolbarSelected = document.querySelector(
      '[data-kt-lembaga-table-toolbar="selected"]'
    );
    const selectedCount = document.querySelector(
      '[data-kt-lembaga-table-select="selected_count"]'
    );

    const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

    let checkedState = false;
    let count = 0;

    allCheckboxes.forEach((c) => {
      if (c.checked) {
        checkedState = true;
        count++;
      }
    });

    if (checkedState) {
      selectedCount.innerHTML = count;
      toolbarBase.classList.add("d-none");
      toolbarSelected.classList.remove("d-none");
    } else {
      toolbarBase.classList.remove("d-none");
      toolbarSelected.classList.add("d-none");
    }
  };
  var handleEditForm = () => {
    const editButtons = document.querySelectorAll(
      '[data-kt-lembaga-table-filter="edit_row_lembaga"]'
    );
    editButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        $.ajax({
          url: "lembagas/lembagas_action.php?pg=show",
          type: "POST",
          data: { id: id },
          success: function (response) {
            $("#lembaga_id").val(response.id);
            $("#lembaga_fullname").val(response.fullname);
            $("#lembaga_lembaganame").val(response.lembaganame);
            $("#lembaga_phone").val(response.phone);
            $("#lembaga_role").val(response.role).trigger("change");
            $("#lembaga_gender").val(response.gender).trigger("change");
            if (response.status === 1) {
              $("#lembaga_status").prop("checked", true);
            } else {
              $("#lembaga_status").prop("checked", false);
            }
            var modal = new bootstrap.Modal(
              document.getElementById("kt_lembaga_form_modal")
            );
            modal.show();
          },
          error: function () {},
        });
      });
    });
  };
  var handleOpenANdResetModal = () => {
    const openModalAdd = document.querySelectorAll(
      '[data-kt-lembaga-table-filter="add_lembaga"]'
    );
    openModalAdd.forEach((button) => {
      button.addEventListener("click", () => {
        $("#lembaga_id").val("");
        $("#lembaga_fullname").val("");
        $("#lembaga_lembaganame").val("");
        $("#lembaga_phone").val("");
        $("#lembaga_role").val("");
        $("#lembaga_gender").val("");
        $("#lembaga_status").prop("checked", false);
        var modal = new bootstrap.Modal(
          document.getElementById("kt_lembaga_form_modal")
        );
        modal.show();
      });
    });
  };

  var initForm = function () {
    var tags = new Tagify(form.querySelector('[name="tags"]'), {
      whitelist: ["Important", "Urgent", "High", "Medium", "Low"],
      maxTags: 5,
      dropdown: {
        maxItems: 10,
        enabled: 0,
        closeOnSelect: false,
      },
    });
    tags.on("change", function () {
      validator.revalidateField("tags");
    });
    $(form.querySelector('[name="team_assign"]')).on("change", function () {
      validator.revalidateField("team_assign");
    });
  };
  var handleForm = function () {
    validator = FormValidation.formValidation(form, {
      fields: {
        fullname: {
          validators: {
            notEmpty: {
              message: "Full Name is required",
            },
          },
        },
        lembaganame: {
          validators: {
            notEmpty: {
              message: "Username is required",
            },
          },
        },
        phone: {
          validators: {
            notEmpty: {
              message: "Phone is required",
            },
          },
        },
        role: {
          validators: {
            notEmpty: {
              message: "Role is required",
            },
          },
        },
        gender: {
          validators: {
            notEmpty: {
              message: "Due Date is required",
            },
          },
        },
        password: {
          validators: {
            callback: {
              message: "Password is required",
              callback: function (input) {
                var id = $("#lembaga_id").val();
                if (id === "" && input.value.trim() === "") {
                  return false;
                }
                return true;
              },
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
              $("#lembaga_id").val() !== ""
                ? "lembagas/lembagas_action.php?pg=update"
                : "lembagas/lembagas_action.php?pg=store";
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
                dt.ajax.reload();
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
        if (result.isConfirmed) {
          form.reset();
          modal.hide();
        }
      });
    });
  };

  return {
    init: function () {
      modalEl = document.querySelector("#kt_lembaga_form_modal");
      if (!modalEl) {
        return;
      }
      modal = new bootstrap.Modal(modalEl);
      initDatatable();
      handleSearchDatatable();
      bulkActionSelected();
      handleFilterDatatable();
      handleDeleteRows();
      handleResetForm();
      handleEditForm();
      handleOpenANdResetModal();
      form = document.querySelector("#kt_lembaga_form_modal_form");
      submitButton = document.getElementById("kt_lembaga_form_modal_submit");
      cancelButton = document.getElementById("kt_lembaga_form_modal_cancel");
      initForm();
      handleForm();
    },
  };
})();
document.addEventListener("DOMContentLoaded", function () {
  KTDatatableLembagasSide.init();
});
