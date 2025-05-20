# Academic Email Verifier

A PHP package to verify if an email address belongs to an academic or research institution. This package helps you validate whether an email address is from a recognized academic institution.

## Live Demo

Try the package live at: [verify-academic-email.asamoahboateng.com](https://verify-academic-email.asamoahboateng.com)

## Installation

You can install the package via Composer:

```bash
composer require asamoahboateng/academic-email-verifier
```

## Requirements

- PHP 8.2 or higher
- JSON extension

## Usage

### Basic Usage

```php
use AcademicEmailVerifier\AcademicEmailVerifier;

$verifier = new AcademicEmailVerifier();

// Verify an academic email
$result = $verifier->verify('student@harvard.edu');

// Check the result
if ($result['is_academic']) {
    echo "This is an academic email from: " . $result['university'];
} else {
    echo "Error: " . $result['error'];
}
```

### Available Methods

#### verify(string $email): array
Verifies if an email belongs to an academic institution. Returns an array with:
- `is_academic` (boolean): Whether the email is from an academic institution
- `university` (string|null): The name of the university if found
- `error` (string|null): Error message if verification fails

#### isValidEmail(string $email): bool
Checks if the email format is valid.

### Example Results

```php
// Academic email
$result = $verifier->verify('student@harvard.edu');
// Result:
// [
//     'is_academic' => true,
//     'university' => 'Harvard University',
//     'error' => null
// ]

// Non-academic email
$result = $verifier->verify('user@gmail.com');
// Result:
// [
//     'is_academic' => false,
//     'university' => null,
//     'error' => 'Domain not found in academic institutions database'
// ]

// Invalid email format
$result = $verifier->verify('invalid-email');
// Result:
// [
//     'is_academic' => false,
//     'university' => null,
//     'error' => 'Invalid email format'
// ]
```

## Testing

Run the test suite using PHPUnit:

```bash
composer test
```

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

- Kwame Boateng
- Email: kwame@asamoahboateng.com

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security related issues, please email kwame@asamoahboateng.com instead of using the issue tracker.
