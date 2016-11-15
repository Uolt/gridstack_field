<?php

namespace Drupal\gridstack_field;




interface GridstackFieldHelperInterface {
  /**
   * Return array with keys of options for gridstack plugin separated by type.
   *
   * @param string $type
   *   Determine which type of options should be returned.
   *
   * @return array
   *   An array with options keys.
   */
  public function getOptions($type);

  /**
   * Get enables content types displays.
   *
   * @param $type
   *
   * @return array
   *   An array with displays.
   */
  public function getDisplays($type);
}