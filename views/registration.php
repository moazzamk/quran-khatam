<style type="text/css">
    .form-field-container {
        margin-bottom: 20px;
    }
    .form-field-container .mdc-text-field {
        width: 100%;
    }
    .form-field-container ul {
        list-style-type: none;
        margin-left: 5px;
    }
    .form-field-container .mdc-button {
        padding: 30px 35px;
    }
    .khatam-registration-form {
        padding: 16px;
        border: 3px #000 solid;
    }
</style>
<form
      id="khatam-registration-form"
      action="<?= esc_url( admin_url( 'admin-post.php' ) ); ?>"
      class="khatam-registration-form"
      method="post"
>
    <input type="hidden" name="action" value="khatam-save-registration" />
    <div class="form-field-container">
        <div>
            <b>Please select an option</b>
            <span style="color: red">*</span>
        </div>
        <ul>
            <li>
                <div class="mdc-touch-target-wrapper">
                    <div class="mdc-radio mdc-radio--touch">
                        <input class="mdc-radio__native-control" type="radio" id="radio-1" name="khatam-action" checked value="register">
                        <div class="mdc-radio__background">
                            <div class="mdc-radio__outer-circle"></div>
                            <div class="mdc-radio__inner-circle"></div>
                        </div>
                        <div class="mdc-radio__ripple"></div>
                    </div>
                    <label for="radio-1">I want to recite a juz</label>
                </div>
            </li>
            <li>
                <div class="mdc-touch-target-wrapper">
                    <div class="mdc-radio mdc-radio--touch">
                        <input class="mdc-radio__native-control" type="radio" id="radio-2" name="khatam-action" value="finished">
                        <div class="mdc-radio__background">
                            <div class="mdc-radio__outer-circle"></div>
                            <div class="mdc-radio__inner-circle"></div>
                        </div>
                        <div class="mdc-radio__ripple"></div>
                    </div>
                    <label for="radio-1">I completed my juz recitation</label>
                </div>
            </li>
        </ul>
    </div>
    <div class="form-field-container">
        <label class="mdc-text-field mdc-text-field--filled">
            <span class="mdc-text-field__ripple"></span>
            <span class="mdc-floating-label" id="my-label-id">
                Name (first, last)
                <span style="color: red">*</span>
            </span>
            <input class="mdc-text-field__input texty" name="name" type="text" aria-labelledby="my-label-id">
            <span class="mdc-line-ripple"></span>
        </label>
    </div>
    <div class="form-field-container">
        <label class="mdc-text-field mdc-text-field--filled">
            <span class="mdc-text-field__ripple"></span>
            <span class="mdc-floating-label" id="my-label-id">
                Email
                <span style="color: red">*</span>
            </span>
            <input class="mdc-text-field__input" name="email" type="text" aria-labelledby="my-label-id">
            <span class="mdc-line-ripple"></span>
        </label>
    </div>
    <div class="form-field-container" style="text-align: right">
        <div class="mdc-touch-target-wrapper">
            <button class="mdc-button mdc-button--raised">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__touch"></span>
                <span class="mdc-button__label">SUBMIT</span>
            </button>
        </div>
    </div>
</form>
