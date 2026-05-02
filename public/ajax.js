$(document).ready(function() {
    $("#signup-email, #signup-fname, #signup-lname, #signup-pwd, #signup-repeatpwd").blur(function() {
        validateInputs("signup",true, false);
    });

    $("#signup-form").submit(function(event) {
        event.preventDefault();
        validateInputs("signup", false, true);
    })

    $("#login-email, #login-pwd").blur(function() {
        validateInputs("login",true, false);
    });

    $("#login-form").submit(function(event) {
        event.preventDefault();
        validateInputs("login", false, true);
    })

    function validateInputs(type, isLiveCheck, isFinalSubmit)
    {
        if(type === "signup")
        {
            var email = $("#signup-email").val();
            var fname = $("#signup-fname").val();
            var lname = $("#signup-lname").val();
            var password = $("#signup-pwd").val();
            var repeatedpassword = $("#signup-repeatpwd").val();

            $(".signup .error-message").load("../src/signup.php", {
                email: email,
                fname: fname,
                lname: lname,
                password: password,
                repeatedpassword: repeatedpassword,
                submit: 'true',
                isLive: isLiveCheck
            }, function(responseText) {
                if(responseText.includes("success") && isFinalSubmit){
                    window.location.href = "../public/index.php?signup=success";
                }
            });
        }
        else if(type === "login"){
            var email = $("#login-email").val();
            var password = $("#login-pwd").val();

            $.ajax({
                url: "../src/login.php",
                type: "POST",
                data: {
                    email: email,
                    password: password
                },
                dataType: "json",
                success: function(data) {

                    if(data.status === "success") {
                // ALWAYS clear errors on success
                $(".login .error-message").html("");

                if(isFinalSubmit) {
                    window.location.href = data.redirect;
                }

            } else if (data.status === "error") {
                $(".login .error-message").html(data.message);
            }

            },
        
    });
}
    }})
