/**
 * Created by jeyfost on 10.11.2017.
 */

function addService() {
	var name = $('#serviceNameInput').val();
	var sef_link = $('#serviceSefLinkInput').val();
	var text = $('#serviceTextInput').val();
	var list = $('#serviceListInput').val();
	var formData = new FormData($('#serviceForm').get(0));

	if(name !== '') {
		if(sef_link !== '') {
            if(text !== '') {
                if(list !== '') {
                    $.ajax({
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        processData: false,
                        contentType: false,
                        url: "/scripts/admin/services/ajaxAddService.php",
                        beforeSend: function () {
                            $.notify("Идёт добавление услуги...", "info");
                        },
                        success: function (response) {
                            switch(response) {
                                case "ok":
                                    $.notify("Услуга была успешно добавлена.", "success");

                                    setTimeout(function () {
                                        href.location = "/admin/services/";
                                    }, 2000);
                                    break;
                                case "failed":
                                    $.notify("Произошла ошибка. Попробуйте снова.", "error");
                                    break;
                                case "duplicate":
                                    $.notify("Услуга с таким названием уже существует.", "error");
                                    break;
                                case "photo":
                                    $.notify("Выбрано изображение неподходящего формата.", "error");
                                    break;
                                case "photo_empty":
                                    $.notify("Фотография услуги не выбрана.", "error");
                                    break;
                                case "sef_link":
                                    $.notify("Введённый адресс сылки уже существует.", "error");
                                    break;
                                default:
                                    $.notify(response, "warn");
                                    break;
                            }
                        }
                    });
                } else {
                    $.notify("Необходимо ввести пункты услуги.", "error");
                }
            } else {
                $.notify("Необходимо ввести описание услуги", "error");
            }
		} else {
            $.notify("Необходимо ввести адресс сылки услуги", "error");
		}
	} else {
		$.notify("Необходимо ввести название услуги", "error");
	}
}