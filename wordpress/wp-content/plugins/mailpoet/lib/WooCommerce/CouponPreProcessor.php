<?php declare(strict_types = 1);

namespace MailPoet\WooCommerce;

if (!defined('ABSPATH')) exit;


use MailPoet\Entities\NewsletterEntity;
use MailPoet\Newsletter\NewslettersRepository;
use MailPoet\Newsletter\Renderer\Blocks\Coupon;
use MailPoet\WP\DateTime;

class CouponPreProcessor {

  /** @var NewslettersRepository */
  private $newslettersRepository;

  /** @var Helper */
  private $wcHelper;

  public function __construct(
    Helper $wcHelper,
    NewslettersRepository $newslettersRepository
  ) {
    $this->wcHelper = $wcHelper;
    $this->newslettersRepository = $newslettersRepository;
  }

  public function processCoupons(NewsletterEntity $newsletter, array $blocks, bool $preview = false): array {
    if ($preview) {
      return $blocks;
    }

    $generated = $this->ensureCouponForBlocks($blocks, $newsletter);
    $body = $newsletter->getBody();

    if ($generated && $body && $this->shouldPersist($newsletter)) {
      $updatedBody = array_merge(
        $body,
        [
          'content' => array_merge(
            $body['content'],
            ['blocks' => $blocks]
          ),
        ]
      );
      $newsletter->setBody($updatedBody);
      $this->newslettersRepository->flush();
    }
    return $blocks;
  }

  private function ensureCouponForBlocks(array &$blocks, NewsletterEntity $newsletter): bool {

    static $generated = false;
    foreach ($blocks as &$innerBlock) {
      if (isset($innerBlock['blocks']) && !empty($innerBlock['blocks'])) {
        $this->ensureCouponForBlocks($innerBlock['blocks'], $newsletter);
      }
      if (isset($innerBlock['type']) && $innerBlock['type'] === Coupon::TYPE) {
        $generated = $this->shouldGenerateCoupon($innerBlock);
        $innerBlock['couponId'] = $this->addOrUpdateCoupon($innerBlock, $newsletter);
      }
    }

    return $generated;
  }

  private function addOrUpdateCoupon(array $couponBlock, NewsletterEntity $newsletter): int {
    $coupon = $this->wcHelper->createWcCoupon($couponBlock['couponId'] ?? '');
    if ($this->shouldGenerateCoupon($couponBlock)) {
      $code = isset($couponBlock['code']) && $couponBlock['code'] !== Coupon::CODE_PLACEHOLDER ? $couponBlock['code'] : $this->generateRandomCode();
      $coupon->set_code($code);
    }
    $coupon->set_discount_type($couponBlock['discountType']);
    $coupon->set_amount($couponBlock['amount']);
    $expiration = (new DateTime())->getCurrentDateTime()->modify("+{$couponBlock['expiryDay']} day")->getTimestamp();
    $coupon->set_date_expires($expiration);
    $coupon->set_description(
      sprintf(
      // translators: %1$s is newsletter id and %2$s is the subject.
        _x('Auto Generated coupon by MailPoet for email: %1$s: %2$s', 'Coupon block code generation', 'mailpoet'),
        $newsletter->getId(),
        $newsletter->getSubject()
      )
    );

    return $coupon->save();
  }

  /**
   * Generates Coupon code for XXXX-XXXXXX-XXXX pattern
   */
  private function generateRandomCode(): string {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $length = strlen($chars);
    return sprintf(
      "%s-%s-%s",
      substr($chars, rand(0, $length - 5), 4),
      substr($chars, rand(0, $length - 8), 7),
      substr($chars, rand(0, $length - 5), 4)
    );
  }

  private function shouldGenerateCoupon(array $block): bool {
    return empty($block['couponId']);
  }
  
  /**
   * For some renders/send outs the coupon id shouldn't be persisted along the coupon block
   * This is a placeholder method and should be augmented with more newsletter types that should dynamically get coupons
   * and not have one single coupon saved along with the block's settings
   */
  private function shouldPersist(NewsletterEntity $newsletter): bool {
    return $newsletter->getType() !== NewsletterEntity::TYPE_AUTOMATIC;
  }
}
