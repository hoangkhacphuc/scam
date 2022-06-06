
const formPost = document.querySelector('.form-post')
const btnSubmit = document.querySelector('.btn-submit')
const loader = document.querySelector('.loader')

// Toast
function showSuccessToast() {
    toast({
        title: "Thành công!",
        message: "Đã gửi duyệt. Vui lòng chờ Admin xét duyệt",
        type: "success",
        duration: 3000
    });
}

// Resest
function resest() {
    document.querySelector('#accountHolder').value = ''
    document.querySelector('#phoneNumber').value = ''
    document.querySelector('#accountNumber').value = ''
    document.querySelector('#bank').value = ''
    document.querySelector('#image').value = ''
    document.querySelector('#content').value = ''
    document.querySelector('#authorName').value = ''
    document.querySelector('#authorPhone').value = ''
}


Validator({
    form: '.form-post',
    formGroupSelector: '.form-item',
    errorSelector: '.form-message',
    rules: [
        Validator.isRequired('#accountHolder', 'Vui lòng nhập tên chủ tài khoản'),
        Validator.minLength('#accountHolder', 6),
        Validator.isRequired('#accountNumber', 'Vui lòng nhập STK '),
        Validator.minLength('#accountNumber', 10),
        Validator.isRequired('#bank', 'Vui lòng nhập tên ngân hàng'),
        Validator.minLength('#bank', 3),
        Validator.isRequired('#content', 'Vui lòng nhập nội dung tố cáo'),
        Validator.isRequired('#authorName', 'Vui lòng nhập tên của bạn'),
        Validator.minLength('#authorName', 6),
        Validator.isRequired('#authorPhone', 'Vui lòng nhập SĐT của bạn'),
        Validator.minLength('#authorPhone', 10),
    ],
    onSubmit: async function({accountHolder, phoneNumber, accountNumber, bank, image, content, option, authorName, authorPhone}) {
        btnSubmit.classList.add("is-loading");
        await fetch(scammerPendingApi, {
            method: 'POST',
            body: JSON.stringify({
                accountHolder,
                phoneNumber,
                accountNumber,
                bank,
                image,
                content,
                option,
                authorName,
                authorPhone,
            }),
            headers: {
                'Content-type': 'application/json; charset=UTF-8',
            },
        })
        btnSubmit.classList.remove("is-loading");
        showSuccessToast()
        resest()
    }
});


$(document).ready(function () {
    $(document).on('click', '#btn-report', function () {
        let accountHolder = $('#accountHolder').val();
        let phoneNumber = $('#phoneNumber').val();
        let accountNumber = $('#accountNumber').val();
        let bank = $('#bank').val();
        let image = $('#image').val();
        let content = $('#content').val();
        let option = $('input[name="option"]').val();
        let authorName = $('#authorName').val();
        let authorPhone = $('#authorPhone').val();

        let data = {
            name : accountHolder,
            phone : phoneNumber,
            card_number : accountNumber,
            bank : bank,
            content : content,
            name_auth : authorName,
            phone_auth : authorPhone,
            victim : option,
            image : image,
        }

        // post data to ./BE.php?api=Report
        $.post('../BE.php?api=Report', data, function (response) {
                response = JSON.parse(response);
                if (response.status == true) {
                    swal("Thành công!", response.message, "success");
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                } else {
                    swal("Thất bại!", response.message, "error");
                }
            }
        );

    });
});