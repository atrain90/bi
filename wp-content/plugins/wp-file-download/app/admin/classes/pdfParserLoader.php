<?php

/**
 * Class WpfdPdfParser
 */
class WpfdPdfParser
{
    /**
     * Call parser
     *
     * @return \Smalot\PdfParser\Parser
     */
    function init()
    {
        $parser = new \Smalot\PdfParser\Parser();
        return $parser;
    }
}
