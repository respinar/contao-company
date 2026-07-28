<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */


// Back end modules
$GLOBALS['BE_MOD']['company'] = [];

// Insert 'company' group after 'content'
if (isset($GLOBALS['BE_MOD']['content'])) {
    $companyModules = $GLOBALS['BE_MOD']['company'];
    unset($GLOBALS['BE_MOD']['company']);

    $keys = array_keys($GLOBALS['BE_MOD']);
    $contentIndex = array_search('content', $keys, true);

    if ($contentIndex !== false) {
        $before = array_slice($GLOBALS['BE_MOD'], 0, $contentIndex + 1, true);
        $after = array_slice($GLOBALS['BE_MOD'], $contentIndex + 1, null, true);
        $GLOBALS['BE_MOD'] = array_merge($before, ['company' => $companyModules], $after);
    } else {
        $GLOBALS['BE_MOD']['company'] = $companyModules;
    }
}
