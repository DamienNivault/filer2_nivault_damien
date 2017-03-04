$(document).ready(function() {
    var login= $('#login');
    var register= $('#register');

    login.click(function(){
        window.location.href = "?action=login";
    });
    register.click(function(){
        window.location.href = "?action=register";
    });
});