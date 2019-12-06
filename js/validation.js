$(document).ready(function() {
    $("#register").validate({
        rules: {
            appyear:{
                required:true,
                number:true,
                maxlength:4
            },
            indexYear:"required",
            name: "required",
            indexNumber:{
                required:true,
                minlength:10,
                maxlength:10
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                number: true
            },
            url: {
                required: false,
                url: true
            },
            username: {
                required: true,
                minlength: 6
            },
            password: {
                required: true,
                minlength: 8
            },
            confirm_password: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
            
            programmeMajorID: "required",
            secondProgrammeMajorID:"required",
            
            agree: "required"
            
        },
        messages: {
            indexNumber: {
                required: "Please Enter Valid Index Number",
                maxlength: "The Maximum Length is 10",
                minlength:"The Minimum Length is 10"
            },
            name: "Please enter your name",
            email: "Please enter a valid email address",
            phone: {
                required: "Please enter your phone number",
                number: "Please enter only numeric value"
            },
            url: {
                url: "Please enter valid url"
            },
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 6 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long"
            },
            confirm_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 8 characters long",
                equalTo: "Please enter the same password as above"
            },
            
            agree: "Please accept our policy"
        }
    });
    
      $('#indexNumber').mask('TCCCC/SSSS', {'translation': {
            T: {pattern: /[EePpSsUu]/},
            C: {pattern: /[0-9]/},
            S: {pattern: /[0-9]/}
        }
    });
});

