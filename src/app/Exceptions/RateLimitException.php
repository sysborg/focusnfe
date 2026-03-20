<?php

namespace Sysborg\FocusNfe\app\Exceptions;

use RuntimeException;

/**
 * Exceção disparada quando o rate limit da API FocusNFe é excedido
 */
class RateLimitException extends RuntimeException
{
}
