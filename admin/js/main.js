/* =========================================== */
/* =========================================== */


$(document).ready(function () {
    $('#btn-login').click(function (e) { 
        e.preventDefault();
        let username = $('#username').val();
        let password = $('#password-field').val();

        let data = {
            username: username,
            password: password
        }

        if (username == "" || password == "") {
            swal({
                title: "",
                text: "Bạn chưa điền đầy đủ thông tin đăng nhập...",
                icon: "error",
                close: true,
                button: "Thử lại",
              });
              return;
        }
        $.post('../BE.php?api=Login', data, function (response) {
            response = JSON.parse(response);
            if (response.status == true) {
                swal("Thành công!", response.message, "success");
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            } else {
                swal("Thất bại!", response.message, "error");
            }
        }
    );

    });
});
