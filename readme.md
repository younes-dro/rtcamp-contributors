

# Challenge-2: WordPress-Contributors Plugin

A WordPress plugin to display more than one author name on a post. This plugin allows you to assign multiple contributors to a single post and displays their names and Gravatars at the end of the post content.

## Features

- Assign multiple contributors to a single post.
- Display contributors' names and Gravatars at the end of the post.
- Contributors' names are clickable links leading to their author pages.
- Fully compatible with WordPress coding standards.

## Installation

1. Clone the repository or download the ZIP file.
2. Upload the plugin to your WordPress site's `wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

1. In the WordPress post editor, find the "Contributors" meta box.
2. Select one or more contributors (authors, editors, or admins) for the post.
3. Save the post, and the selected contributors will be displayed at the end of the post content.

## Screenshots

### Contributor Selection in Post Editor
![Contributor Selection](path_to_screenshot_1)

### Contributors Displayed in Post
![Contributors Display](path_to_screenshot_2)

## Testing

To run all test cases, follow these steps:

1. **Set up the testing environment**:
    - Install the necessary dependencies using Composer:
      ```bash
      composer install
      ```
    - Ensure the WordPress testing suite is installed.

2. **Run the tests**:
    - Run all test cases using PHPUnit:
      ```bash
      phpunit
      ```
    - To run a specific test, use the `--filter` option:
      ```bash
      phpunit --filter test_method_name
      ```

## Contributing

If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are welcome.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [PHPUnit](https://phpunit.de/) - Testing framework used in this project.
- [Yoast PHPUnit Polyfills](https://github.com/Yoast/PHPUnit-Polyfills) - Ensuring compatibility with different PHPUnit versions.
