<?php
/**
 * Validation Helper
 * 
 * Input validation functions
 */

/**
 * Validate email format
 * 
 * @param string $email
 * @return bool
 */
function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate required field
 * 
 * @param mixed $value
 * @return bool
 */
function validateRequired($value): bool
{
    if (is_string($value)) {
        return trim($value) !== '';
    }
    return !empty($value);
}

/**
 * Validate minimum length
 * 
 * @param string $value
 * @param int $min
 * @return bool
 */
function validateMinLength(string $value, int $min): bool
{
    return strlen($value) >= $min;
}

/**
 * Validate maximum length
 * 
 * @param string $value
 * @param int $max
 * @return bool
 */
function validateMaxLength(string $value, int $max): bool
{
    return strlen($value) <= $max;
}

/**
 * Validate numeric
 * 
 * @param mixed $value
 * @return bool
 */
function validateNumeric($value): bool
{
    return is_numeric($value);
}

/**
 * Validate NIS format
 * 
 * @param string $nis
 * @return bool
 */
function validateNIS(string $nis): bool
{
    return preg_match('/^[0-9]{5,20}$/', $nis);
}

/**
 * Validate phone number
 * 
 * @param string $phone
 * @return bool
 */
function validatePhone(string $phone): bool
{
    // Indonesian phone format
    return preg_match('/^(\+62|62|0)[0-9]{9,12}$/', preg_replace('/\s+/', '', $phone));
}

/**
 * Validate date format
 * 
 * @param string $date
 * @param string $format
 * @return bool
 */
function validateDate(string $date, string $format = 'Y-m-d'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Validate time format
 * 
 * @param string $time
 * @return bool
 */
function validateTime(string $time): bool
{
    return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $time);
}

/**
 * Sanitize string input
 * 
 * @param string $input
 * @return string
 */
function sanitizeString(string $input): string
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize email
 * 
 * @param string $email
 * @return string
 */
function sanitizeEmail(string $email): string
{
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

/**
 * Validate form data
 * 
 * @param array $data
 * @param array $rules
 * @return array Errors
 */
function validateForm(array $data, array $rules): array
{
    $errors = [];

    foreach ($rules as $field => $fieldRules) {
        $value = $data[$field] ?? '';
        $label = ucfirst(str_replace('_', ' ', $field));

        foreach ($fieldRules as $rule => $param) {
            switch ($rule) {
                case 'required':
                    if ($param && !validateRequired($value)) {
                        $errors[$field] = "{$label} harus diisi";
                    }
                    break;

                case 'email':
                    if ($param && !empty($value) && !validateEmail($value)) {
                        $errors[$field] = "Format {$label} tidak valid";
                    }
                    break;

                case 'min':
                    if (!empty($value) && !validateMinLength($value, $param)) {
                        $errors[$field] = "{$label} minimal {$param} karakter";
                    }
                    break;

                case 'max':
                    if (!empty($value) && !validateMaxLength($value, $param)) {
                        $errors[$field] = "{$label} maksimal {$param} karakter";
                    }
                    break;

                case 'numeric':
                    if ($param && !empty($value) && !validateNumeric($value)) {
                        $errors[$field] = "{$label} harus berupa angka";
                    }
                    break;

                case 'phone':
                    if ($param && !empty($value) && !validatePhone($value)) {
                        $errors[$field] = "Format {$label} tidak valid";
                    }
                    break;

                case 'nis':
                    if ($param && !empty($value) && !validateNIS($value)) {
                        $errors[$field] = "Format {$label} tidak valid (5-20 digit)";
                    }
                    break;
            }

            // Stop on first error for this field
            if (isset($errors[$field])) {
                break;
            }
        }
    }

    return $errors;
}
