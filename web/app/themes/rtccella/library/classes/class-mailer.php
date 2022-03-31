<?php

namespace WP_Lemon\Classes;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

use function WP_Lemon\Child\logger;

class Mail
{
   const SKIP_ON_DEV_AND_STAGING = false;
   const LOG_TYPE = 'mailer';
   /**
    * Array or String of emails where to send
    * @var mixed
    */
   protected $emails;

   /**
    * Subject of email
    * @var string
    */
   protected string $title;

   /**
    * Associative Array of dynamic data
    * @var array
    */
   protected array $dynamicData = array();

   /**
    * Template used to send data
    * @var string
    */
   protected string $template;

   /**
    * Prepared template with real data instead of placeholders
    * @var string
    */
   protected string $outputTemplate;

   /**
    * Prefix for logging events.
    * @var string
    */
   protected string $log_prefix;

   protected bool $skip_on_dev_and_staging;

   public function __construct($emails, string $title, string $mail_inner, string $log_prefix)
   {
      $this->log_prefix = $log_prefix;
      $this->emails = $emails;
      $this->title = $title;
      $this->prepareTemplate($mail_inner);
      return $this->send();
   }

   private function prepareTemplate($inner)
   {
      $this->outputTemplate = $inner;
   }

   private function send()
   {
      $headers = array('Content-Type: text/html; charset=UTF-8', 'Reply-To: RTC Cella <info@rtccella.nl>', 'From: RTC Cella <website@mg.rtccella.nl>');
      $error = '';
      $cssToInlineStyles = new CssToInlineStyles();
      $html = $this->outputTemplate;
      $css = file_get_contents(__DIR__ . '/email.css');

      $template = $cssToInlineStyles->convert(
         $html,
         $css
      );

      if (true == self::SKIP_ON_DEV_AND_STAGING && 'production' !== getenv('WP_ENV')) {
         $result = 'testmode';
      } else {
         try {
            $result =  wp_mail($this->emails, $this->title, $template, $headers);
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
