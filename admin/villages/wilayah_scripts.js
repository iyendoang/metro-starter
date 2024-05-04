var KTWilayahIndonesia = (function () {
  var getRegency = function () {
    var province_id = $("#province_province_id").val();
    if (province_id != "") {
      $.ajax({
        url: "../config/controller/wilayah_controller.php?pg=getRegency",
        type: "post",
        data: {
          province_id: province_id,
        },
        dataType: "json",
        success: function (response) {
          $("#regency_regency_id").empty(); // Kosongkan opsi yang ada sebelum menambahkan yang baru
          var len = response.length;
          for (var i = 0; i < len; i++) {
            var id = response[i]["id"];
            var name = response[i]["regency_name"];
            $("#regency_regency_id").append(
              "<option value='" + id + "'>" + name + "</option>"
            );
          }
          // Inisialisasi select2 pada pilihan Kota
          $("#regency_regency_id").select2().trigger("change");
        },
      });
    }
  };

  var getDistrict = function () {
    var regency_id = $("#regency_regency_id").val();
    if (regency_id != "") {
      $.ajax({
        url: "../config/controller/wilayah_controller.php?pg=getDistrict",
        type: "post",
        data: {
          regency_id: regency_id,
        },
        dataType: "json",
        success: function (response) {
          $("#district_district_id").empty(); // Kosongkan opsi yang ada sebelum menambahkan yang baru
          var len = response.length;
          for (var i = 0; i < len; i++) {
            var id = response[i]["id"];
            var name = response[i]["district_name"];
            $("#district_district_id").append(
              "<option value='" + id + "'>" + name + "</option>"
            );
          }
          // Inisialisasi select2 pada pilihan Kecamatan
          $("#district_district_id").select2().trigger("change");
        },
      });
    }
  };

  var initForm = function () {
    getRegency();
    $("#province_province_id").change(getRegency);
    getDistrict();
    $("#regency_regency_id").change(getDistrict);
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
