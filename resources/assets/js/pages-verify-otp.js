/**
 * Page auth two steps (Updated)
 * - Allows OTP copy & paste
 * - Removes delay
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (() => {
    const maskWrapper = document.querySelector('.numeral-mask-wrapper');
    const twoStepsForm = document.querySelector('#twoStepsForm');

    if (!maskWrapper || !twoStepsForm) return;

    const inputs = Array.from(maskWrapper.querySelectorAll('.numeral-mask'));
    const hiddenOtpField = twoStepsForm.querySelector('[name="otp"]');

    // Ensure numeric only input
    inputs.forEach((input, index) => {
      input.addEventListener('input', e => {
        // Keep only digits
        input.value = input.value.replace(/\D/g, '');

        // Auto focus next if full
        if (input.value.length === 1 && index < inputs.length - 1) {
          inputs[index + 1].focus();
        }

        updateOtpValue();
      });

      // Backspace navigation
      input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && input.value === '' && index > 0) {
          inputs[index - 1].focus();
        }
      });

      // Allow paste of full OTP
      input.addEventListener('paste', e => {
        e.preventDefault();

        let pasted = (e.clipboardData || window.clipboardData).getData('text');
        pasted = pasted.replace(/\D/g, '').substring(0, inputs.length);

        // Fill inputs sequentially
        [...pasted].forEach((digit, i) => {
          if (inputs[i]) inputs[i].value = digit;
        });

        // Clear remaining boxes
        for (let i = pasted.length; i < inputs.length; i++) {
          inputs[i].value = '';
        }

        updateOtpValue();

        // Move focus to the last filled box
        const targetIndex = pasted.length >= inputs.length ? inputs.length - 1 : pasted.length;
        inputs[targetIndex].focus();
      });
    });

    // Update hidden input value
    function updateOtpValue() {
      let otp = inputs.map(i => i.value).join('');
      hiddenOtpField.value = otp.length === inputs.length ? otp : '';
    }

    // Init validation
    if (typeof FormValidation !== 'undefined') {
      FormValidation.formValidation(twoStepsForm, {
        fields: {
          otp: {
            validators: {
              notEmpty: {
                message: 'Please enter OTP'
              },
              stringLength: {
                min: 6,
                max: 6,
                message: 'OTP must be 6 digits'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.form-control-validation'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        }
      });
    }
  })();
});
