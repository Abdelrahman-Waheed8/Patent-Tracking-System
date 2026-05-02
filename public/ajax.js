$(document).ready(function() {
    $("input").blur(function() {
        validateInputs(true, false);
    });

    $("form").submit(function(event) {
        event.preventDefault();
        validateInputs(false, true);
    })

    function validateInputs(isLiveCheck, isFinalSubmit)
    {
        var email = $("#signup-email").val();
        var fname = $("#signup-fname").val();
        var lname = $("#signup-lname").val();
        var password = $("#signup-pwd").val();
        var repeatedpassword = $("#signup-repeatpwd").val();

        $(".error-message").load("../src/signup.php", {
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
});