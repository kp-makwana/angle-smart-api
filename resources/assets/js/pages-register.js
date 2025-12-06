'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (() => {
    const formAuthentication = document.querySelector('#formAuthentication');

    if (formAuthentication && typeof FormValidation !== 'undefined') {
      FormValidation.formValidation(formAuthentication, {
        fields: {
          name: {
            validators: {
              notEmpty: {
                message: 'Please enter your name'
              },
              stringLength: {
                min: 2,
                message: 'Name must be at least 2 characters'
              }
            }
          },

          email: {
            validators: {
              notEmpty: {
                message: 'Please enter your email'
              },
              emailAddress: {
                message: 'Please enter a valid email address'
              }
            }
          },

          password: {
            validators: {
              notEmpty: {
                message: 'Please enter your password'
              },
              stringLength: {
                min: 6,
                message: 'Password must be at least 6 characters'
              }
            }
          },

          password_confirmation: {
            validators: {
              notEmpty: {
                message: 'Please confirm your password'
              },
              identical: {
                compare: () => formAuthentication.querySelector('[name="password"]').value,
                message: 'Password and confirmation do not match'
              }
            }
          },

          terms: {
            validators: {
              notEmpty: {
                message: 'Please agree to the terms & conditions'
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
        },

        init: instance => {
          instance.on('plugins.message.placed', e => {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }
  })();
});
