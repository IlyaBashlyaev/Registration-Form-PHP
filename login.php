<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://fonts.googleapis.com/css2?family=Itim&Nunito:wght@600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="includes/style.css">
        <title>Registration</title>
    </head>

    <body onmouseup="rippleHide()">
        <div class="alert" style='opacity: 0; z-index: 0;'>
            <div class="inner-alert" style='z-index: 0;'>
                <div class="alert-text"></div>
                <input type="text" class="inner-form-input code" onclick="wrongClick(this)" style='display: none;' placeholder="Verification code">
                <div class="alert-button">
                    <button onclick='removeAlert()'>Ok</button>
                </div>
            </div>
        </div>

        <div class="bg" style="background-image: url(http://unsplash.it/1920/1080?gravity=center); width: 125vw; height: 125vh;"></div>

        <?php
            if (isset($_POST['username'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
            }
        ?>

        <main>
            <form action='/sign-up.php' method='post'>
                <input type="hidden" class='token_response' name='token_response'>
                
                <div class="mode">
                    <div class="sign sign-up clicked" onclick='changeMode(0)'>Sign Up</div>
                    <div class="sign sign-in" onclick='changeMode(1)'>Sign In</div>
                </div>

                <div class="form-input username first-child" onclick='inputClick(0)'>
                    <input name='username' type='text' class="inner-form-input username" placeholder="Enter username:" onclick="wrongClick(this)"
                        <?php
                            if (isset($username)) {echo " value='$username' ";}
                        ?>
                    maxlength="100">
                </div>

                <div class="form-input email" onclick='inputClick(1)'>
                    <input name='email' type='email' class="inner-form-input email" placeholder="Enter email:" onclick="wrongClick(this)"
                        <?php
                            if (isset($email)) {echo " value='$email' ";}
                        ?>
                    maxlength="100">
                </div>

                <div class="form-input password" onclick='inputClick(2)'>
                    <input name='password' type='password' class="inner-form-input password" placeholder="Enter password:" onclick="wrongClick(this)"
                        <?php
                            if (isset($password)) {echo " value='$password' ";}
                        ?>
                    maxlength="100">

                    <div class="form-input-img">
                        <div onmousedown="rippleShow(this)"
                            style='background-image: url(static/closed_eye.png)'></div>
                    </div>
                </div>

                <input type="hidden" name='modeIndex' value='0'>
                <input type="hidden" name='type' value='TempUser'>

                <div class="login">
                    <button type="button" onclick='login()'>Sign Up</button>
                </div>
            </form>
        </main>

        <script src="https://www.google.com/recaptcha/api.js?render=6Ldq8ZwbAAAAAN98ra5XtDtLZoUrMg6TJmIHCHMm"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute('6Ldq8ZwbAAAAAN98ra5XtDtLZoUrMg6TJmIHCHMm', {action: 'submit'}).then(function(token) {
                    var response = document.querySelector('.token_response')
                    response.value = token
                })
            })
        </script>

        <script>
            var isAlert = false
            var canBeClosed = true

            var isClicked = false
            var isCorrect = true
            var isPassword = true
            var isFormEmail = false
            var isFormPassword = false

            var lastModeIndex = 0
            var lastFormIndex = 0
            var button

            const a = document.createElement('a')
            const bg = document.querySelector('.bg')

            var formInput
            var Value

            var main = document.querySelector('main')
            main.onclick = (e) => {
                if(e.target == this) {
                    if (!isClicked) {
                        bg.style.width = '150vw'
                        bg.style.height = '150vh'
                        isClicked = true
                    }

                    else {
                        bg.style.width = '125vw'
                        bg.style.height = '125vh'
                        isClicked = false
                    }
                }
            }

            function changeMode(modeIndex) {
                const sign = document.querySelectorAll('.sign')

                if (modeIndex != lastModeIndex) {
                    sign[modeIndex].classList.add('clicked')
                    sign[lastModeIndex].classList.remove('clicked')

                    const form = document.querySelector('form')
                    var input = form.querySelector('input[name="modeIndex"]')
                    input.value = '' + modeIndex

                    const login = form.querySelector('.login button')
                    const username = document.querySelector('form > .username')
                    const email = document.querySelector('form > .email')

                    if (modeIndex == 1) {
                        form.style.height = '63vh'
                        form.action = 'sign/sign-in.php'
                        login.innerText = 'Sign In'

                        username.style.display = 'none'
                        username.classList.remove('first-child')
                        email.classList.add('first-child')
                    }

                    else {
                        form.style.height = '79vh'
                        form.action = 'sign/sign-up.php'
                        login.innerText = 'Sign Up'

                        username.style.display = 'flex'
                        username.classList.add('first-child')
                        email.classList.remove('first-child')
                    }

                    lastModeIndex = modeIndex
                }
            }

            function inputClick(formIndex) {
                formInput = document.querySelectorAll('.inner-form-input:not(.code)')

                if (formIndex != lastFormIndex) {
                    formInput[formIndex].classList.add('clicked')
                    formInput[lastFormIndex].classList.remove('clicked')
                    lastFormIndex = formIndex
                }
            }

            function addAlert(text) {
                const form = document.querySelector('form')
                form.style.filter = 'blur(25px)'

                const alert = document.querySelector('.alert')
                const innerAlert = alert.querySelector('.inner-alert')

                alert.style.zIndex = '1'
                innerAlert.style.zIndex = '1'
                alert.style.opacity = '.8'

                const alertText = alert.querySelector('.alert-text')
                alertText.innerText = text

                isAlert = true
            }

            function removeAlert() {
                const form = document.querySelector('form')
                form.style.filter = 'none'

                const alert = document.querySelector('.alert')
                const innerAlert = alert.querySelector('.inner-alert')

                alert.style.opacity = '0'
                innerAlert.style.zIndex = '0'
                alert.style.zIndex = '0'

                isAlert = false
            }

            function login() {
                const formInputs = document.querySelectorAll('.inner-form-input:not(.code)')

                for (var index = 0; index < 3; index++) {
                    formInput = formInputs[index]
                    Value = formInput.value

                    if (
                        Value == "" &&
                        !(index == 0 && lastModeIndex == 1)
                    ) {
                        var name = formInput.name
                        formInput.classList.add('wrong')
                        addAlert(`The field value "${name}" is empty`)
                        return
                    }

                    else if (index == 1) {
                        var isEmail = validateEmail(Value)

                        if (!isEmail) {
                            formInput.classList.add('wrong')
                            addAlert('You email address is uncorrect')
                            return
                        }

                        Value = Value.split('@')
                        Value = [Value[0].split('+')[0], Value[1]]
                        Value[0] = Value[0].split('.').join('')
                        Value = Value.join('@').toLowerCase()
                        formInput.value = Value
                    }
                }

                const form = document.querySelector('form')
                if (lastModeIndex == 0) {form.action = 'sign-up.php'}
                else if (lastModeIndex == 1) {form.action = 'sign-in.php'}

                const alert = document.querySelector('.alert')
                alert.style.zIndex = '1'

                const button = document.createElement('button')
                form.appendChild(button)

                button.type = 'submit'
                button.click()
            }

            function wrongClick(formInput) {
                if (formInput.classList.contains('wrong')) {
                    formInput.classList.remove('wrong')
                }
            }

            function validateEmail(email) {
                const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                return re.test(String(email).toLowerCase())
            }

            function changePassword(_isPassword) {
                const input = document.querySelectorAll('.inner-form-input:not(.code)')[2]
                const img = document.querySelector('.form-input-img div')

                if (_isPassword) {
                    input.type = 'text'
                    img.style.backgroundImage = 'url("static/opened_eye.png")'
                }
                
                else {
                    input.type = 'password'
                    img.style.backgroundImage = 'url("static/closed_eye.png")'
                }

                isPassword = !_isPassword
            }

            function rippleShow(el) {
                var rippleEl = document.querySelector('span.ripple')

                if (!rippleEl) {var rippleEl = document.createElement('span')}
                else {rippleEl.classList.remove('hide')}

                el.appendChild(rippleEl)
                var max = Math.max(el.offsetWidth, el.offsetHeight)
                rippleEl.style.width = rippleEl.style.height = max + 'px'

                var rect = el.getBoundingClientRect()
                rippleEl.style.left = event.clientX - rect.left - (max / 2) + 'px'
                rippleEl.style.top = event.clientY - rect.top - (max / 2) + 'px'

                rippleEl.classList.add('ripple')
            }

            function rippleHide() {
                const ripples = document.querySelectorAll('.ripple')

                ripples.forEach(ripple => {
                    ripple.classList.add('hide')
                })
            }

            const img = document.querySelector('.form-input-img div')
            img.addEventListener('mouseup', () => {
                changePassword(isPassword)
            })

            document.addEventListener('keydown', e => {
                const loginButton = document.querySelector('.login button')
                if (e.keyCode == 13 && !loginButton.clicked) {
                    login()
                }

                if (e.keyCode == 27 && isAlert) {
                    removeAlert()
                }
            })
        </script>

        <?php
            if (isset($_POST['error'])):
                ?>

                <script>
                    addAlert("<?= $_POST['error'] ?>")
                </script>

                <?php
            endif;
        ?>
    </body>
</html>
