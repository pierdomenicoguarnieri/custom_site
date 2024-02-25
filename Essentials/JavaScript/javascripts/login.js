function showRegister(flag){
    login = document.getElementById('login-div')
    register = document.getElementById('register-div')
    if(flag == false){
        register.style.display = 'flex';
        login.style.display = 'none';
    }else{
        login.style.display = 'flex';
        register.style.display = 'none';
    }
    hideModal()
}