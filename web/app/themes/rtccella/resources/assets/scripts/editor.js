import domReady from '@wordpress/dom-ready';
import backend from 'parentThemeScripts/components/functions-backend';
domReady(() => {
    backend();

    acf.addAction('render_block_preview', function (el, block) {
        // add your custom block js here.
    });
});
