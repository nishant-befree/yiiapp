<?php

use pbackend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);

$this->beginPage();

echo $content;

$this->endPage();
