<?php

/**
 * Mailer class
 *
 * @package WordPress
 * @subpackage WP_Lemon\Child
 */

namespace WP_Lemon\Classes;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

use function WP_Lemon\Child\logger;

/**
 * Custom wrapper around the wp_mail function.
 */
class Mail
{
	/**
	 * If we want to skip on dev and staging.
	 */
	const SKIP_ON_DEV_AND_STAGING = false;

	/**
	 * The log event.
	 */
	const LOG_TYPE = 'mailer';
	/**
	 * Array or String of emails where to send
	 *
	 * @var mixed
	 */
	protected $emails;

	/**
	 * Subject of email
	 *
	 * @var string
	 */
	protected string $title;

	/**
	 * Template used to send data
	 *
	 * @var string
	 */
	protected string $template;

	/**
	 * Prefix for logging events.
	 *
	 * @var string
	 */
	protected string $log_prefix;

	/**
	 * The output.
	 *
	 * @var string
	 */
	protected string $output_template;
	/**
	 * The constructor.
	 *
	 * @param array  $emails The email adresses to send to.
	 * @param string $title The subject of the email.
	 * @param string $mail_inner the content of the email.
	 * @param string $log_prefix The prefix for the log.
	 */
	public function __construct($emails, string $title, string $mail_inner, string $log_prefix)
	{
		$this->log_prefix = $log_prefix;
		$this->emails = $emails;
		$this->title = $title;
		$this->prepare_template($mail_inner);
		return $this->send();
	}

	/**
	 * Prepare the template.
	 *
	 * @param string $inner The content of the email.
	 * @return void
	 */
	private function prepare_template($inner)
	{
		$this->output_template = $inner;
	}

	/**
	 * Sends the mail.
	 *
	 * @return string result of the mailer.
	 */
	private function send()
	{
		$headers = ['Content-Type: text/html; charset=UTF-8', 'Reply-To: RTC Cella <info@rtccella.nl>', 'From: RTC Cella <website@mg.rtccella.nl>'];
		$error = '';
		$css_to_inline_styles = new CssToInlineStyles();
		$html = $this->output_template;
		$css = file_get_contents(__DIR__ . '/email.css');

		$template = $css_to_inline_styles->convert(
			$html,
			$css
		);

		if (true == self::SKIP_ON_DEV_AND_STAGING && 'production' !== getenv('WP_ENV')) {
			$result = 'testmode';
		} else {
			try {
				$result = wp_mail($this->emails, $this->title, $template, $headers);
			} catch (\Exception $e) {
				$error = $e->getMessage();
			}
		}

		if ('testmode' === $result) {
			$result = 'successful';
			$log_message = 'Not Sent 🡆 Development/staging environment!';
		} else if ($result) {
			$result = 'successful';
			$log_message = 'Sent';
		} else {
			$result = 'error';
			$log_message = 'Error' . PHP_EOL;
			$log_message .= $error;
		}

		logger(__METHOD__, $this->log_prefix, $log_message);
		return $result;
	}
}
