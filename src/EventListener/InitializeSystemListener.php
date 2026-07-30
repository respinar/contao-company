<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook("initializeSystem")]
class InitializeSystemListener
{
  public function __construct(
    private readonly RequestStack $requestStack,
    private readonly ScopeMatcher $scopeMatcher,
    private readonly Packages $packages,
  ) {}

  /**
   * Load the CSS file for the back end navigation group icon.
   */
  public function __invoke(): void
  {
    // Reorder BE_MOD to place 'company' immediately after 'content'.
    // Runs unconditionally because initializeSystem fires after all bundles
    // (including child bundles) have registered their backend modules.
    if (
      isset($GLOBALS["BE_MOD"]["company"]) &&
      isset($GLOBALS["BE_MOD"]["content"])
    ) {
      $companyModules = $GLOBALS["BE_MOD"]["company"];
      unset($GLOBALS["BE_MOD"]["company"]);

      $keys = array_keys($GLOBALS["BE_MOD"]);
      $contentIndex = array_search("content", $keys, true);

      if ($contentIndex !== false) {
        $before = array_slice($GLOBALS["BE_MOD"], 0, $contentIndex + 1, true);
        $after = array_slice($GLOBALS["BE_MOD"], $contentIndex + 1, null, true);
        $GLOBALS["BE_MOD"] = array_merge(
          $before,
          ["company" => $companyModules],
          $after,
        );
      } else {
        $GLOBALS["BE_MOD"]["company"] = $companyModules;
      }
    }
    $request = $this->requestStack->getCurrentRequest();

    if (!$request || !$this->scopeMatcher->isBackendRequest($request)) {
      return;
    }

    $GLOBALS["TL_CSS"][] = $this->packages->getUrl(
      "css/backend.css",
      "respinar_company",
    );
  }
}
