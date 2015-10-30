<?php

/**
 * general function & static page
 */
class Pddk_Overview extends Pddk_Core {

    /**
     * overview page
     */
    public function overview()
    {
        $this->view('v_overview');
    }

    /**
     * help page
     */
    public function helps()
    {
        $this->view('v_help');
    }

}
