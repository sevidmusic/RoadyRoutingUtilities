<?php

namespace Darling\RoadyRoutingUtilities\interfaces\routers;

use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response;


/**
 * Description of this interface.
 *
 */
interface Router
{

    public function handleRequest(Request $request): Response;

}

