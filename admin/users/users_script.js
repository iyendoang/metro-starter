"use strict";
var KTDatatablesServerSide = (function () {
  var dt;
  var filterRole;
  var status = {
    0: { title: "Non Active", state: "danger" },
    1: { title: "Active", state: "success" },
    2: { title: "Over due", state: "warning" },
  };
  var submitButton;
  var cancelButton;
  var validator;
  var form;
  var modal;
  var modalEl;
  var initDatatable = function () {
    dt = $("#kt_datatable_example_1").DataTable({
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
        url: "users/users_action.php?pg=index",
      },
      columns: [
        { data: "id" },
        { data: "fullname" },
        { data: "gender" },
        { data: "phone" },
        { data: "role" },
        { data: "status" },
        { data: null },
      ],
      columnDefs: [
        {
          targets: 0,
          orderable: false,
          render: function (data, type, row) {
            return `
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="${row["id"]}" data-id="${row["id"]}"/>
                    </div>`;
          },
        },
        {
          targets: 1,
          className: "d-flex align-items-center",
          render: function (data, type, row) {
            var statusObj = status[row.status];
            return `	<div class="symbol symbol-circle symbol-30px overflow-hidden me-3">
                    <a href="view.html">
                      <div class="symbol-label fs-3 bg-light-${statusObj.state} text-${statusObj.state}">${row["fullname_short"]}</div>
                    </a>
                  </div>
                  <div class="d-flex flex-column">
                    <a href="view.html" class="text-gray-800 text-hover-primary mb-1">${row["fullname"]}</a>
                    <span>${row["username"]}</span>
          </div>`;
          },
        },
        {
          targets: 5,
          render: function (data, type, row) {
            var statusObj = status[row.status];
            return `<div class="badge badge-light-${statusObj.state} fw-bolder">${statusObj.title}</div>`;
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
                  <a href="#" class="menu-link px-3" data-id="${data["id"]}" data-kt-user-table-filter="edit_row_user">
                    Edit
                  </a>
                </div>
                <div class="menu-item px-3">
                  <a href="#" data-id="${data["id"]}" class="menu-link px-3" data-kt-user-table-filter="delete_row_user">
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
      bulkActionSelected();
      toggleToolbars();
      handleDeleteRows();
      handleEditForm();
      KTMenu.createInstances();
    });
  };

  var handleDeleteRows = () => {
    const deleteButtons = document.querySelectorAll(
      '[data-kt-user-table-filter="delete_row_user"]'
    );
    deleteButtons.forEach((d) => {
      d.addEventListener("click", function (e) {
        e.preventDefault();
        const parent = e.target.closest("tr");
        const id = d.dataset.id;
        const userFullName = parent.querySelectorAll("td")[1].innerText;
        Swal.fire({
          text: "Are you sure you want to delete " + userFullName + "?",
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
              url: "users/users_action.php?pg=delete",
              type: "POST",
              data: { id: id },
              success: function (response) {
                if (response.status === 200) {
                  Swal.fire({
                    text: "You have deleted " + userFullName + "!.",
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
                    text: "Failed to delete " + userFullName + ".",
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
                  text: "Failed to delete " + userFullName + ".",
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
              text: userFullName + " was not deleted.",
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
      '[data-kt-user-table-filter="search"]'
    );
    filterSearch.addEventListener("keyup", function (e) {
      dt.search(e.target.value).draw();
    });
  };
  var handleFilterDatatable = () => {
    filterRole = document.querySelectorAll(
      '[data-kt-user-table-filter="role_type"]'
    );
    const filterButton = document.querySelector(
      '[data-kt-user-table-filter="filter"]'
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
      '[data-kt-user-table-filter="reset"]'
    );
    if (resetButton) {
      resetButton.addEventListener("click", function () {
        dt.search("").draw();
      });
    }
  };
  var bulkActionSelected = function () {
    const container = document.querySelector("#kt_datatable_example_1");
    const checkboxes = container.querySelectorAll('[type="checkbox"]');
    const deleteSelected = document.querySelector(
      '[data-kt-user-table-select="delete_selected"]'
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
            text: "Please select at least one user to delete",
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
          text: "Are you sure you want to delete selected users?",
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
              url: "users/users_action.php?pg=bulk_delete",
              type: "POST",
              data: { ids: selectedIds },
              success: function (response) {
                if (response.status === 200) {
                  Swal.fire({
                    text: "You have deleted all selected users!",
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
                    text: "Failed to delete selected users.",
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
                  text: "Failed to delete selected users.",
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
    const container = document.querySelector("#kt_datatable_example_1");
    const toolbarBase = document.querySelector(
      '[data-kt-user-table-toolbar="base"]'
    );
    const toolbarSelected = document.querySelector(
      '[data-kt-user-table-toolbar="selected"]'
    );
    const selectedCount = document.querySelector(
      '[data-kt-user-table-select="selected_count"]'
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
      '[data-kt-user-table-filter="edit_row_user"]'
    );
    editButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const id = this.getAttribute("data-id");
        $.ajax({
          url: "users/users_action.php?pg=show",
          type: "POST",
          data: { id: id },
          success: function (response) {
            $("#user_id").val(response.id);
            $("#user_fullname").val(response.fullname);
            $("#user_username").val(response.username);
            $("#user_phone").val(response.phone);
            $("#user_role").val(response.role).trigger("change");
            $("#user_gender").val(response.gender).trigger("change");
            if (response.status === 1) {
              $("#user_status").prop("checked", true);
            } else {
              $("#user_status").prop("checked", false);
            }
            var modal = new bootstrap.Modal(
              document.getElementById("kt_user_form_modal")
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
      '[data-kt-user-table-filter="add_user"]'
    );
    openModalAdd.forEach((button) => {
      button.addEventListener("click", () => {
        $("#user_id").val("");
        $("#user_fullname").val("");
        $("#user_username").val("");
        $("#user_phone").val("");
        $("#user_role").val("");
        $("#user_gender").val("");
        $("#user_status").prop("checked", false);
        var modal = new bootstrap.Modal(
          document.getElementById("kt_user_form_modal")
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
        username: {
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
                var id = $("#user_id").val();
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
              $("#user_id").val() !== ""
                ? "users/users_action.php?pg=update"
                : "users/users_action.php?pg=store";
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
      modalEl = document.querySelector("#kt_user_form_modal");
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
      form = document.querySelector("#kt_user_form_modal_form");
      submitButton = document.getElementById("kt_user_form_modal_submit");
      cancelButton = document.getElementById("kt_user_form_modal_cancel");
      initForm();
      handleForm();
    },
  };
})();
document.addEventListener("DOMContentLoaded", function () {
  KTDatatablesServerSide.init();
});
