<?php

namespace App\Exceptions;

use Exception;

/**
 * Represents an expected, user-facing business rule violation in the
 * booking/cancellation flow (sales not started, sold out, not cancellable...).
 *
 * This is NOT a bug: it is thrown deliberately when a request cannot be
 * fulfilled for a reason the user can understand and act on. It is caught
 * by the renderable registered in bootstrap/app.php and turned into a
 * normal redirect-back with a flash error message, instead of an
 * exception page. Genuine unexpected errors must keep using regular
 * exceptions so they are logged and surfaced as a 500 page.
 */
class BookingException extends Exception
{
    //
}
