"use strict";
var KTSignupGeneral = (function () {
  var form;
  var submitButton;
  var validator;
  var passwordMeter;

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
            regexp: {
              regexp: /^[a-zA-Z0-9]+$/,
              message: "Username can only contain letters and numbers",
            },
          },
        },
        gender: {
          validators: {
            notEmpty: {
              message: "Gender is required",
            },
          },
        },
        phone: {
          validators: {
            notEmpty: {
              message: "Phone number is required",
            },
            stringLength: {
              max: 14,
              message: "Phone number must be less than 14 digits",
            },
            regexp: {
              regexp: /^[0-9]+$/,
              message: "Phone number can only contain numbers",
            },
          },
        },
        province_id: {
          validators: {
            notEmpty: {
              message: "Province is required",
            },
          },
        },
        regency_id: {
          validators: {
            notEmpty: {
              message: "Regency is required",
            },
          },
        },
        district_id: {
          validators: {
            notEmpty: {
              message: "District is required",
            },
          },
        },
        school_npsn: {
          validators: {
            notEmpty: {
              message: "School NPSN is required",
            },
          },
        },
        password: {
          validators: {
            notEmpty: {
              message: "Password is required",
            },
            callback: {
              message: "Please enter a valid password",
              callback: function (input) {
                if (input.value.length > 0) {
                  return validatePassword();
                }
              },
            },
          },
        },
        "confirm-password": {
          validators: {
            notEmpty: {
              message: "Password confirmation is required",
            },
            identical: {
              compare: function () {
                return form.querySelector('[name="password"]').value;
              },
              message: "The password and its confirm are not the same",
            },
          },
        },
        toc: {
          validators: {
            notEmpty: {
              message: "You must accept the terms and conditions",
            },
          },
        },
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger({
          event: {
            password: false,
          },
        }),
        bootstrap: new FormValidation.plugins.Bootstrap5({
          rowSelector: ".fv-row",
          eleInvalidClass: "",
          eleValidClass: "",
        }),
      },
    });

    submitButton.addEventListener("click", function (e) {
      e.preventDefault();
      validator.revalidateField("password");
      validator.validate().then(function (status) {
        if (status === "Valid") {
          submitButton.setAttribute("data-kt-indicator", "on");
          submitButton.disabled = true;
          var formData = new FormData(form);
          $.ajax({
            url: "config/controller/act_register.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
              if (response.success) {
                var successMessage =
                  response.message || "You have successfully registered!";
                setTimeout(function () {
                  submitButton.removeAttribute("data-kt-indicator");
                  submitButton.disabled = false;
                  Swal.fire({
                    text: successMessage,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  }).then(function (result) {
                    if (result.isConfirmed) {
                      form.reset();
                      passwordMeter.reset();
                      window.location.href = "login.php"; 
                    }
                  });
                }, 1500);
              } else {
                submitButton.removeAttribute("data-kt-indicator");
                submitButton.disabled = false;
                var errorMessage =
                  response.message || "Registration failed. Please try again.";
                Swal.fire({
                  text: errorMessage,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
              }
            },
            error: function () {
              submitButton.removeAttribute("data-kt-indicator");
              submitButton.disabled = false;
              Swal.fire({
                text: "An error occurred. Please try again later.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
            },
          });
        }
      });
    });

    form
      .querySelector('input[name="password"]')
      .addEventListener("input", function () {
        if (this.value.length > 0) {
          validator.updateFieldStatus("password", "NotValidated");
        }
      });
  };

  var validatePassword = function () {
    return passwordMeter.getScore() === 100;
  };

  var getProvince = function () {
    $.ajax({
      url: "config/controller/wilayah_controller.php?pg=getProvince",
      type: "post",
      data: {
        province: "ok",
      },
      dataType: "json",
      success: function (response) {
        $("#province_id").empty();
        $("#province_id").append(
          "<option value=''>" + "Select Province" + "</option>"
        );
        if (response) {
          var len = response.length;
          for (var i = 0; i < len; i++) {
            var id = response[i]["id"];
            var name = response[i]["province_name"];
            $("#province_id").append(
              "<option value='" + id + "'>" + name + "</option>"
            );
          }
        }
        $("#province_id").select2().trigger("change");
      },
    });
  };
  var getRegency = function () {
    var province_id = $("#province_id").val();
    if (province_id != "") {
      $.ajax({
        url: "config/controller/wilayah_controller.php?pg=getRegency",
        type: "post",
        data: {
          province_id: province_id,
        },
        dataType: "json",
        success: function (response) {
          $("#regency_id").empty();
          $("#regency_id").append(
            "<option value=''>" + "Select Regency" + "</option>"
          );
          if (response) {
            var len = response.length;
            for (var i = 0; i < len; i++) {
              var id = response[i]["id"];
              var name = response[i]["regency_name"];
              $("#regency_id").append(
                "<option value='" + id + "'>" + name + "</option>"
              );
            }
          }
          $("#regency_id").select2().trigger("change");
          getDistrict();
        },
      });
    }
  };
  var getDistrict = function () {
    var regency_id = $("#regency_id").val();
    if (regency_id != "") {
      $.ajax({
        url: "config/controller/wilayah_controller.php?pg=getDistrict",
        type: "post",
        data: {
          regency_id: regency_id,
        },
        dataType: "json",
        success: function (response) {
          $("#district_id").empty();
          $("#district_id").append(
            "<option value=''>" + "Select District" + "</option>"
          );
          if (response) {
            var len = response.length;
            for (var i = 0; i < len; i++) {
              var id = response[i]["id"];
              var name = response[i]["district_name"];
              $("#district_id").append(
                "<option value='" + id + "'>" + name + "</option>"
              );
            }
          }
          $("#district_id").select2().trigger("change");
          getSchools();
        },
      });
    }
  };
  var getSchools = function () {
    var district_id = $("#district_id").val();
    if (district_id != "") {
      $.ajax({
        url: "config/controller/wilayah_controller.php?pg=getSchools",
        type: "post",
        data: {
          district_id: district_id,
        },
        dataType: "json",
        success: function (response) {
          $("#school_npsn").empty();
          $("#school_npsn").append(
            "<option value=''>" + "Select Sekolah" + "</option>"
          );
          if (response) {
            var len = response.length;
            for (var i = 0; i < len; i++) {
              var npsn = response[i]["school_npsn"];
              var name = response[i]["school_name"];
              $("#school_npsn").append(
                "<option value='" +
                  npsn +
                  "'>" +
                  npsn +
                  " - " +
                  name +
                  "</option>"
              );
            }
          }
          $("#school_npsn").select2().trigger("change");
        },
      });
    }
  };
  var initForm = function () {
    getProvince();
    $("#province_id").on("change", getRegency);
    $("#regency_id").on("change", getDistrict);
    $("#district_id").on("change", getSchools);
  };

  return {
    init: function () {
      // Elements
      form = document.querySelector("#kt_sign_up_form");
      submitButton = document.querySelector("#kt_sign_up_submit");
      passwordMeter = KTPasswordMeter.getInstance(
        form.querySelector('[data-kt-password-meter="true"]')
      );
      initForm();
      handleForm();
    },
  };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
  KTSignupGeneral.init();
});
