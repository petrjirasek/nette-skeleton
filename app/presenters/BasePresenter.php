<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    protected function createComponentCss()
    {
        $files = new WebLoader\FileCollection(WWW_DIR . '/css');
        $files->addFiles(array(
            WWW_DIR . '/bootstrap/css/bootstrap.css',
            WWW_DIR . '/bootstrap/css/responsive.css',
            'styles.css',
        ));

        $compiler = WebLoader\Compiler::createCssCompiler($files, WWW_DIR . '/temp');
/*        $compiler->addFilter(function ($code) {
            return cssmin::minify($code, "remove-last-semicolon");
        });*/

        $control = new WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/temp');
        $control->setMedia('screen');

        return $control;
    }


    protected function createComponentJs()
    {
        $files = new WebLoader\FileCollection(WWW_DIR . '/js');
        $files->addRemoteFile('http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js');
        $files->addFiles(array(
            WWW_DIR . '/bootstrap/js/bootstrap.min.js',
            'netteForms.js',
        ));

        $compiler = WebLoader\Compiler::createJsCompiler($files, WWW_DIR . '/temp');
        /*        $compiler->addFilter(function ($code) {
            return cssmin::minify($code, "remove-last-semicolon");
        });*/

        $control = new WebLoader\Nette\JavaScriptLoader($compiler, $this->template->basePath . '/temp');

        return $control;
    }
}
