"use strict";

var KTSigninGeneral = (function () {
  var form;
  var submitButton;
  var validator;

  var handleForm = function () {
    validator = FormValidation.formValidation(form, {
      fields: {
        username: {
          validators: {
            notEmpty: {
              message: "Username address is required",
            }
          },
        },
        password: {
          validators: {
            notEmpty: {
              message: "The password is required",
            },
          },
        },
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap: new FormValidation.plugins.Bootstrap5({
          rowSelector: ".fv-row",
        }),
      },
    });

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      validator.validate().then(function (status) {
        if (status === "Valid") {
          submitButton.setAttribute("data-kt-indicator", "on");
          submitButton.disabled = true;
          var formData = new FormData(form);
          $.ajax({
            url: "config/controller/act_login.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
              if (response.success) {
                Swal.fire({
                  text: response.message,
                  icon: "success",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
                setTimeout(function () {
                  window.location.href = "admin";
                }, 2000);
              } else {
                Swal.fire({
                  text: response.message,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
              }
            },
            complete: function () {
              submitButton.removeAttribute("data-kt-indicator");
              submitButton.disabled = false;
            },
          });
        }
      });
    });
  };

  return {
    init: function () {
      form = document.querySelector("#kt_sign_in_form");
      submitButton = document.querySelector("#kt_sign_in_submit");
      handleForm();
    },
  };
})();
KTUtil.onDOMContentLoaded(function () {
  KTSigninGeneral.init();
});
