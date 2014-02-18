<?php
/*****************************
 * Borrowed from Adaptive images
 *
 * Homepage:  http://adaptive-images.com
 *
 * LEGAL:
 * Adaptive Images by Matt Wilcox is licensed under a Creative Commons Attribution 3.0 Unported License.
 *
 **/
function getResolution()
{
	$resolutions   = array(1382, 992, 768, 480); // the resolution break-points to use (screen widths, in pixels)
	$resolution = false;

	if (isset($_COOKIE['resolution'])) {
    $cookie_value = $_COOKIE['resolution'];

    // does the cookie look valid? [whole number, comma, potential floating number]
    if (! preg_match("/^[0-9]+[,]*[0-9\.]+$/", "$cookie_value")) { // no it doesn't look valid
      $this->cookies->set("resolution", "$cookie_value", time()-100); // delete the mangled cookie
    }
    else { // the cookie is valid, do stuff with it
      $cookie_data   = explode(",", $_COOKIE['resolution']);
      $client_width  = (int) $cookie_data[0]; // the base resolution (CSS pixels)
      $total_width   = $client_width;
      $pixel_density = 1; // set a default, used for non-retina style JS snippet
      if (isset($cookie_data[1])) { // the device's pixel density factor (physical pixels per CSS pixel)
        $pixel_density = $cookie_data[1];
      }

      rsort($resolutions); // make sure the supplied break-points are in reverse size order
      $resolution = $resolutions[0]; // by default use the largest supported break-point

      // if pixel density is not 1, then we need to be smart about adapting and fitting into the defined breakpoints
      if($pixel_density != 1) {
        $total_width = $client_width * $pixel_density; // required physical pixel width of the image

        // the required image width is bigger than any existing value in $resolutions
        if($total_width > $resolutions[0]){
          // firstly, fit the CSS size into a break point ignoring the multiplier
          foreach ($resolutions as $break_point) { // filter down
            if ($total_width <= $break_point) {
              $resolution = $break_point;
            }
          }
          // now apply the multiplier
          $resolution = $resolution * $pixel_density;
        }
        // the required image fits into the existing breakpoints in $resolutions
        else {
          foreach ($resolutions as $break_point) { // filter down
            if ($total_width <= $break_point) {
              $resolution = $break_point;
            }
          }
        }
      }
      else { // pixel density is 1, just fit it into one of the breakpoints
        foreach ($resolutions as $break_point) { // filter down
          if ($total_width <= $break_point) {
            $resolution = $break_point;
          }
        }
      }
    }
  }

  /* No resolution was found (no cookie or invalid cookie) */
  if (!$resolution) {
    // We send the lowest resolution for mobile-first approach, and highest otherwise
    $resolution = isMobile() ? min($resolutions) : max($resolutions);
  }
  return $resolution;
}
?>