$(document).ready(function() {
    var login= $('#login');
    var register= $('#register');

    login.click(function(){
        window.location.href = "?action=login";
    });
    register.click(function(){
        window.location.href = "?action=register";
    });
    // Get the modal
    var modal = document.getElementById('myModal');

// Get the button that opens the modal
    var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    };

// When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    };

// When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
    function emailValidation(email) {
        var emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return emailRegExp.test(email);
    }
    function usernameValidation(username){
        var userRegExp =  /^((?!SELECT)(?!SELECT *)(?!DROP)(?!select)(?!drop)(?!insert)(?!update)(?!DELETE)(?!DELETE *)(?!INSERT)(?! DATABASE)(?!UPDATE)(?!DROP TABLE).)*$/;
        return userRegExp.test(username);
    }
    var registerForm = $('register');
    var errorBlockEmail = $('#errorBlockEmail');
    errorBlockEmail = '';
    registerForm.submit(function(){
        var $username = $('#usernameRegister').value;

        var $email = $('#email').val();
        console.log($email);
        if (emailValidation($email) === false) {
            errorBlockEmail.innerHTML = '<br>veuillez saisir une adresse email valide';
            return false
        }
        return true;
    });

});