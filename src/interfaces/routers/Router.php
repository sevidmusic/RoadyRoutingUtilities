<?php

namespace Darling\RoadyRoutingUtilities\interfaces\routers;

use \Darling\RoadyRoutingUtilities\interfaces\requests\Request;
use \Darling\RoadyRoutingUtilities\interfaces\responses\Response;

/**
 * A Router can accept a Request and return an appropriate Response
 * for that Request.
 */
interface Router
{

    /**
     * Return an appropriate Response to the specified Request.
     *
     * @param Request $request The Request to process.
     *
     * @return Response
     *
     */
    public function handleRequest(Request $request): Response;

}

