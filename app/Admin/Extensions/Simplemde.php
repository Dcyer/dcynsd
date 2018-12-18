<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class Simplemde extends Field
{
    protected $view = 'admin::form.editor';

    protected static $css = [
        '/vendor/simplemde/dist/simplemde.min.css',
    ];

    protected static $js = [
        '/vendor/simplemde/dist/simplemde.min.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

 var simplemde = new SimpleMDE({
                autofocus: true,
                autosave: {
                    enabled: true,
                    delay: 10000,
                    unique_id: "editor01",
                },
                spellChecker: false,
                autoDownloadFontAwesome: false,
                tabSize: 4,
                placeholder: "请使用 Markdown 格式书写 ;-)，代码片段黏贴时请注意使用高亮语法。",
                toolbar: [
                    "bold", "italic", "strikethrough", "heading", "code", "quote", "unordered-list",
                    "ordered-list", "clean-block", "link", "image", "table", "horizontal-rule", "preview", "side-by-side", "fullscreen", "guide",
                ],
                renderingConfig: {
                    codeSyntaxHighlighting: true
                }
            });

EOT;
        return parent::render();

    }
}