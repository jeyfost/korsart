function changeServiceButton(action, id) {
    if(action === 1) {
        $('#' + id).css('background-color', '#e6e6e6');
        $('#' + id).css('color', '#474747');
    } else {
        $('#' + id).css('background-color', '#fff');
        $('#' + id).css('color', '#000');
    }
}