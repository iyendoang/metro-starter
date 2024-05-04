var KTWilayahIndonesia = (function () {
  var getRegency = function () {
    var province_id = $("#province_id").val();
    if (province_id != "") {
      $.ajax({
        url: "../config/controller/wilayah_controller.php?pg=getRegency",
        type: "post",
        data: {
          province_id: province_id,
        },
        dataType: "json",
        success: function (response) {
          $("#regency_id").empty();
          var len = response.length;
          for (var i = 0; i < len; i++) {
            var id = response[i]["id"];
            var name = response[i]["regency_name"];
            $("#regency_id").append(
              "<option value='" + id + "'>" + name + "</option>"
            );
          }
          $("#regency_id").select2().trigger("change");
          // Menjalankan fungsi getDistrict setelah regency dipilih
          getDistrict();
        },
      });
    }
  };
  var getDistrict = function () {
    var regency_id = $("#regency_id").val();
    if (regency_id != "") {
      $.ajax({
        url: "../config/controller/wilayah_controller.php?pg=getDistrict",
        type: "post",
        data: {
          regency_id: regency_id,
        },
        dataType: "json",
        success: function (response) {
          $("#district_id").empty();
          var len = response.length;
          for (var i = 0; i < len; i++) {
            var id = response[i]["id"];
            var name = response[i]["district_name"];
            $("#district_id").append(
              "<option value='" + id + "'>" + name + "</option>"
            );
          }
          $("#district_id").select2().trigger("change");
          // Menjalankan fungsi getVillage setelah district dipilih
          getVillage();
        },
      });
    }
  };
  var getVillage = function () {
    var district_id = $("#district_id").val();
    if (district_id != "") {
      $.ajax({
        url: "../config/controller/wilayah_controller.php?pg=getVillage",
        type: "post",
        data: {
          district_id: district_id,
        },
        dataType: "json",
        success: function (response) {
          $("#village_id").empty();
          var len = response.length;
          for (var i = 0; i < len; i++) {
            var id = response[i]["id"];
            var name = response[i]["village_name"];
            $("#village_id").append(
              "<option value='" + id + "'>" + name + "</option>"
            );
          }
          $("#village_id").select2().trigger("change");
        },
      });
    }
  };
  var initForm = function () {
    $("#province_id").on("change", getRegency);
    $("#regency_id").on("change", getDistrict);
    $("#district_id").on("change", getVillage);
  };
  return {
    init: function () {
      initForm();
    },
  };
})();

document.addEventListener("DOMContentLoaded", function () {
  KTWilayahIndonesia.init();
});
