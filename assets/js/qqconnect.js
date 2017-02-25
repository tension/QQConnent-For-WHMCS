function QQ_login( val ) {
    $('body').append('<div class="login_Div"><span class="close" onclick="close_Login();">&times;</span><iframe id="login_frame" name="login_frame" style="margin: 0;padding: 0;" frameborder="0" scrolling="no" width="100%" height="100%" src="./modules/addons/QQ_Connect/oauth/?'+val+'"></iframe></div><div class="mask_Div"></div>');
}

function close_Login() {
    $('.login_Div').remove();
    $('.mask_Div').remove();
}