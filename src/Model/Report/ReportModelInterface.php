<?php


namespace SALESmanago\Model\Report;


interface ReportModelInterface
{
    public function getDataToSend();

    public function setData($data);
}