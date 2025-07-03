function showHidePassword(input_id,toggle_id){
    const passwordInput = document.getElementById(input_id);
    const togglePassword = document.getElementById(toggle_id);
    const isPassword = passwordInput.type === "password";
        if (isPassword){
            passwordInput.type = "text";
            togglePassword.textContent = "visibility";

        }
        else{
            passwordInput.type = "password";
            togglePassword.textContent = "visibility_off";
}}
  

