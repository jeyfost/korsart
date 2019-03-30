function editProduct() {
    var id = $("#productSelect").val();
    var service = $("#serviceSelect").val();
    var name = $("#productNameInput").val();
    var description = $("#productDescriptionInput").val();

    if(name !== "") {
        if(description !== "") {
            $.ajax({
                type: "POST",
                data: {
                    "id": id,
                    "name": name,
                    "description": description
                },
                url: "/scripts/admin/products/ajaxEditProduct.php",
                beforeSend: function () {
                    $.notify("Идёт редактирование...", "info");
                },
                success: function (response) {
                    switch(response) {
                        case "ok":
                            $.notify("Инофрмация о продукте успешно изменена.", "success");

                            setTimeout(function() {
                                location.href = "/admin/products/index.php?service_id=" + service + "&id=" + id;
                            }, 1000);
                            break;
                        case "failed":
                            $.notify("При обновлении информации о продукте произошла ошибка. Попробуйте снова.", "error");
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

function deleteProduct() {
    if(confirm("Вы действительно хотите удалить этот продукт?")) {
        var id = $("#productSelect").val();
        var service = $("#serviceSelect").val();

        $.ajax({
            type: "POST",
            data: {"id": id},
            url: "/scripts/admin/products/ajaxDeleteProduct.php",
            beforeSend: function () {
                $.notify("Продукт удаляется...", "info");
            },
            success: function (response) {
                switch(response) {
                    case "ok":
                        $.notify("Продукт успешно удалён.", "success");

                        setTimeout(function() {
                            location.href = "/admin/products/index.php?service_id=" + service;
                        }, 1000);
                        break;
                    case "failed":
                        $.notify("При удалении продукта произошла ошибка. Попробуйте снова.", "error");
                        break;
                    default:
                        $.notify(response, "warn");
                        break;
                }
            }
        });
    }
}