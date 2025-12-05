<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\TranslationLoader\LanguageLine;

class LanguageLineSeeder extends Seeder
{
  public function run(): void
  {
    $groups = [
      'otp'  => $this->otpTranslations(),
      'auth' => $this->authTranslations(),
      'password' => $this->passwordTranslations(),
    ];

    foreach ($groups as $group => $items) {
      foreach ($items as $key => $text) {
        LanguageLine::updateOrCreate(
          ['group' => $group, 'key' => $key],
          ['text' => $text]
        );
      }
    }
  }

  /**
   * OTP related translations
   */
  private function otpTranslations(): array
  {
    return [
      'subject' => [
        'en' => 'Your OTP Verification Code',
        'hi' => 'आपका ओटीपी सत्यापन कोड',
      ],
      'message' => [
        'en' => 'Your OTP is :otp and it will expire in 10 minutes.',
        'hi' => 'आपका ओटीपी :otp है और यह 10 मिनट में समाप्त हो जाएगा।',
      ],
      'invalid' => [
        'en' => 'Invalid OTP entered.',
        'hi' => 'गलत ओटीपी दर्ज किया गया।',
      ],
      'expired' => [
        'en' => 'OTP has expired. Please request a new one.',
        'hi' => 'ओटीपी की समय सीमा समाप्त हो गई है। कृपया नया ओटीपी मांगें।',
      ],
      'verified' => [
        'en' => 'Your email has been verified successfully.',
        'hi' => 'आपका ईमेल सफलतापूर्वक सत्यापित हो गया है।',
      ],
      'resent' => [
        'en' => 'A new OTP has been sent to your email.',
        'hi' => 'नया ओटीपी आपके ईमेल पर भेज दिया गया है।',
      ],
    ];
  }

  /**
   * Authentication related translations
   */
  private function authTranslations(): array
  {
    return [
      'register_success' => [
        'en' => 'Registration successful. Please verify your OTP.',
        'hi' => 'पंजीकरण सफल। कृपया अपना ओटीपी सत्यापित करें।',
      ],
      'email_verify_first' => [
        'en' => 'Please verify your email first.',
        'hi' => 'कृपया पहले अपना ईमेल सत्यापित करें।',
      ],
      'login_success' => [
        'en' => 'Logged in successfully.',
        'hi' => 'सफलतापूर्वक लॉग इन हो गया।',
      ],
      'logout_success' => [
        'en' => 'Logged out successfully.',
        'hi' => 'सफलतापूर्वक लॉग आउट हो गया।',
      ],
      'password_reset_link_sent' => [
        'en' => 'Password reset link sent to your email.',
        'hi' => 'पासवर्ड रीसेट लिंक आपके ईमेल पर भेज दिया गया है।',
      ],
      'password_reset_success' => [
        'en' => 'Your password has been reset successfully.',
        'hi' => 'आपका पासवर्ड सफलतापूर्वक रीसेट किया गया है।',
      ],
    ];
  }

  private function passwordTranslations()
  {
    return [
      'reset_link_sent' => [
        'en' => 'Password reset link has been sent to your email.',
        'hi' => 'पासवर्ड रीसेट लिंक आपके ईमेल पर भेज दिया गया है।',
      ],
      'reset_link_failed' => [
        'en' => 'Unable to send reset link. Please try again.',
        'hi' => 'रीसेट लिंक भेजने में असमर्थ। कृपया पुनः प्रयास करें।',
      ],
      'reset_success' => [
        'en' => 'Your password has been reset successfully.',
        'hi' => 'आपका पासवर्ड सफलतापूर्वक रीसेट कर दिया गया है।',
      ],
      'reset_failed' => [
        'en' => 'Failed to reset password. Try again.',
        'hi' => 'पासवर्ड रीसेट करने में विफल। कृपया पुनः प्रयास करें।',
      ],
    ];
  }
}
