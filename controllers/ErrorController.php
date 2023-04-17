<?php

namespace app\controllers;

use app\core\Controller;

class ErrorController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/errors.css">';
        $js = '<script src="'. ENTORNO .'/public/js/errors.js"></script>';
        $data = [];
        $this->setLayout('error');
        return $this->render('errors/_404', $css, $js, $data);
    }

    public function notAuth()
    {
        $css = '<link rel="stylesheet" href="'. ENTORNO .'/public/css/errors.css">';
        $js = '<script src="'. ENTORNO .'/public/js/errors.js"></script>';
        $data = [];
        $this->setLayout('error');
        return $this->render('errors/not_authorized', $css, $js, $data);
    }
}