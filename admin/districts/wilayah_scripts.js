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
          var len = response.length;
          $("#regency_regency_id").empty(); // Mengosongkan opsi yang ada sebelum menambahkan yang baru
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

  var initForm = function () {
    // Panggil getRegency ketika halaman dimuat pertama kali
    getRegency();

    // Panggil getRegency ketika ada perubahan pada #province_province_id
    $("#province_province_id").change(getRegency);
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
