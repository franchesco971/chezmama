<?php

if (!defined('ABSPATH')) exit;


use MailPoetVendor\Twig\Environment;
use MailPoetVendor\Twig\Error\LoaderError;
use MailPoetVendor\Twig\Error\RuntimeError;
use MailPoetVendor\Twig\Extension\SandboxExtension;
use MailPoetVendor\Twig\Markup;
use MailPoetVendor\Twig\Sandbox\SecurityError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedTagError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFilterError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFunctionError;
use MailPoetVendor\Twig\Source;
use MailPoetVendor\Twig\Template;

/* deactivationPoll/index.html */
class __TwigTemplate_9faa4e87f1ef54ddc698a901ff5f80ef1be75b3ed754540e3a33f2e606c19802 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"mailpoet-deactivate-survey-modal\" id=\"mailpoet-deactivate-survey-modal\">
  <div class=\"mailpoet-deactivate-survey-wrap\">
    <div class=\"mailpoet-deactivate-survey\">
      <script type=\"text/javascript\">
        window.addEventListener('load', function() {
          var deactivateLink = document.querySelector('#the-list [data-slug=\"mailpoet\"] span.deactivate a');
          var overlay = document.querySelector('#mailpoet-deactivate-survey-modal');
          var closeButton = document.querySelector('#mailpoet-deactivate-survey-close');
          var formOpen = false;

          if (!deactivateLink || !overlay || !closeButton) {
            return;
          }

          deactivateLink.addEventListener('click', function (event) {
            event.preventDefault();
            overlay.style.display = 'table';
            formOpen = true;
          });

          closeButton.addEventListener('click', function (event) {
            event.preventDefault();
            overlay.style.display = 'none';
            formOpen = false;
            location.href = deactivateLink.getAttribute('href');
          });

          document.addEventListener('keyup', function (event) {
            if ((event.keyCode === 27) && formOpen) {
              location.href = deactivateLink.getAttribute('href');
            }
          });
        });

        // This callback is by docs, and guarantees that the modal window is closed and deactivated only after the vote has been submitted
        var pd_callback = function(json) {
          var obj = JSON.parse(json);
          var deactivateLink = document.querySelector('#the-list [data-slug=\"mailpoet\"] span.deactivate a');
          var overlay = document.querySelector('#mailpoet-deactivate-survey-modal');
          if (obj.result === 'already-registered' || obj.result === 'registered') {
            overlay.style.display = 'none';
            location.href = deactivateLink.getAttribute('href');
          }
        };
      </script>
      <script type=\"text/javascript\" charset=\"utf-8\" src=\"https://secure.polldaddy.com/p/11161195.js\"></script>

      <noscript><a href=\"https://poll.fm/11161195\">We're sorry to see you leave. Could you tell us more why are you deactivating MailPoet?</a></noscript>

      <a class=\"button\" id=\"mailpoet-deactivate-survey-close\">";
        // line 50
        echo $this->extensions['MailPoet\Twig\I18n']->translate("Skip survey and deactivate MailPoet");
        echo " &rarr;</a>
    </div>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "deactivationPoll/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 50,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "deactivationPoll/index.html", "/home/circleci/mailpoet/mailpoet/views/deactivationPoll/index.html");
    }
}
