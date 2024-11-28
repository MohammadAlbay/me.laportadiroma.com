<?php

namespace App\Services;

class PasswordGeneratorService
{
    /**
     * Generate a strong password.
     *
     * @param int $length
     * @return string
     */
    public function generateStrongPassword($length = 16)
    {
        // Ensure the password length is at least 12 characters (a good practice for security)
        $length = max(10, $length);

        // Define characters to be included in the password
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';

        // Combine all characters into a single string
        $allCharacters = $uppercase . $lowercase . $numbers . $specialChars;

        // Ensure that the password contains at least one uppercase, one lowercase, one number, and one special character
        $password = [
            $uppercase[random_int(0, strlen($uppercase) - 1)],
            $lowercase[random_int(0, strlen($lowercase) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $specialChars[random_int(0, strlen($specialChars) - 1)],
        ];

        // Fill the rest of the password length with random characters from all categories
        for ($i = count($password); $i < $length; $i++) {
            $password[] = $allCharacters[random_int(0, strlen($allCharacters) - 1)];
        }

        // Shuffle the array to make sure the characters are randomly distributed
        shuffle($password);

        // Return the password as a string
        return implode('', $password);
    }
}