function addProduct() {
    var service = $("#serviceSelect").val();
    var name = $("#productNameInput").val();
    var description = $("#productDescriptionInput").val();

    if(name !== "") {
        if(description !== "") {
            $.ajax({
                type: "POST",
                data: {
                    "id": service,
                    "name": name,
                    "description": description
                },
                url: "/scripts/admin/products/ajaxAddProduct.php",
                beforeSend: function () {
                    $.notify("Продукт добавляется...", "info");
                },
                success: function (response) {
                    switch(response) {
                        case "ok":
                            $.notify("Продукт успешно добавлен.", "success");

                            setTimeout(function() {
                                location.href = "/admin/products/index.php?service_id=" + service;
                            }, 1000);
                            break;
                        case "failed":
                            $.notify("При добавлении продукта произошла ошибка. Попробуйте снова.", "error");
                            break;
                        default:
                            $.notify(response, "warn");
                            break;
                    }
                }
            });
        } else {
            $.notify("Необходимо ввести описание продукта.", "error");
        }
    } else {
        $.notify("Необходимо ввести заголовок продукта.", "error");
    }
}